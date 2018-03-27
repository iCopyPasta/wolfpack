package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.app.AlertDialog;
import android.app.DialogFragment;
import android.app.FragmentManager;
import android.app.job.JobInfo;
import android.app.job.JobScheduler;
import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.os.Build;
import android.os.IBinder;
import android.os.PersistableBundle;
import android.support.annotation.RequiresApi;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.support.v4.content.LocalBroadcastManager;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyJobService;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyStartedService;
// given our class id, we ask if there is an active session here!
public class StudentClassPageTab1Sessionlist extends Fragment {

    private static final String TAG = "TCPTab1Sessionlist";

    private String className;
    private String classId;

    private ListView mListViewSessions;
    private static String[] sessionlistTemp = {"Session 01", "Session 02", "Session XD"};

    private MyStartedService mService;

    private final ServiceConnection mServiceConn = new ServiceConnection() {
        @Override
        public void onServiceConnected(ComponentName componentName, IBinder iBinder) {
            MyStartedService.MyServiceBinder myServiceBinder =
                    (MyStartedService.MyServiceBinder) iBinder;

            mService = myServiceBinder.getService();

            if(mService == null){
                Log.e(TAG, "mService is null");
            } else{
                Log.i(TAG, "onServiceConnected: myService is not null: ");
                mService.searchActiveSession(classId, "true");

            }

        }

        @Override
        public void onServiceDisconnected(ComponentName componentName) {
            if(mService != null){
                Log.i(TAG, "onServiceDisconnected: " + TAG + " disconnected from MyStartedService");
            }
        }
    };

    private BroadcastReceiver mReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();
            Log.i(TAG, "onReceive: " + "service message received");

            if(info != null){

                //TODO: show dialog here!
                //TODO: implement dialog interface then
                ActiveSessionDialog activeSessionDialog= new ActiveSessionDialog();
                activeSessionDialog.setInfo(info);

                FragmentManager fragmentManager = getActivity().getFragmentManager();

                activeSessionDialog.show(fragmentManager, TAG);

            }
            else{
                Log.i(TAG, "onReceive: " + "no poll found for class " + classId);
                mService.searchActiveSession(classId, "false");
            }


        }
    };

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        classId = ((StudentClassPage) getActivity()).getClassId();

        View rootView = inflater.inflate(R.layout.fragment_student_class_page_tab1_sessionlist, container, false);

        try {
            //populate list view
            // TODO: Use database to find classes that the student is enrolled
            //       Currently it is displaying a hard coded list for demonstrating purposes

            className = ((StudentClassPage) getActivity()).getClassName();

            mListViewSessions = (ListView) rootView.findViewById(R.id.studentSessionListView);

            ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(
                    getActivity(),
                    android.R.layout.simple_list_item_1,
                    sessionlistTemp);

            mListViewSessions.setAdapter(mAdapter);

            mListViewSessions.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
                    //go to a completed session (no constant checking done on StudentSessionActivePage)
                    Intent intent = new Intent(getActivity(), StudentSessionCompletePage.class);
                    intent.putExtra("className", className);
                    intent.putExtra("sessionName", mListViewSessions.getItemAtPosition(position).toString());
                    intent.putExtra("isActive", false);
                    Log.d(TAG, "starting StudentSessionCompletePage Activity");
                    startActivity(intent);
                }
            });
        }
        catch (NullPointerException e){
            Log.i(TAG, e.toString());
            throw e;
        }

        return rootView;
    }

    @Override
    public void onActivityCreated(Bundle savedInstanceState){
        super.onActivityCreated(savedInstanceState);


    }

    @Override
    public void onStart(){
        super.onStart();

        //bind to custom service
        Intent serviceIntent = new Intent(getContext() , MyStartedService.class);
        getContext().startService(serviceIntent);
        getContext().bindService(serviceIntent, mServiceConn, Context.BIND_AUTO_CREATE);

        LocalBroadcastManager.getInstance(
                getActivity().getApplicationContext())
                .registerReceiver(mReceiver, new IntentFilter(MyStartedService.MY_SERVICE_ACTIVE_SESSION));

    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    public void onResume(){
        super.onResume();

    }

    @Override
    public void onStop(){
        super.onStop();

        getContext().unbindService(mServiceConn);

        LocalBroadcastManager.getInstance(
                getActivity().getApplicationContext())
                .unregisterReceiver(mReceiver);
    }

}
