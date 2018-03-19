package com.wolfpack.cmpsc488.a475layouts.experiences.student;

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

import com.wolfpack.cmpsc488.a475layouts.QuestionPage;
import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyJobService;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyStartedService;

public class StudentClassPageTab1Sessionlist extends Fragment {

    private static final String TAG = "TCPTab1Sessionlist";

    private ListView mListViewSessions;
    private static String[] sessionlistTemp = {"Session 01", "Session 02"};

    //private JobScheduler jobScheduler;
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
            }

            //we can our first initialization of this
            mService.requestActiveSession("string 1", "string 2", "string 3");

        }

        @Override
        public void onServiceDisconnected(ComponentName componentName) {
            if(mService != null){
                Log.i(TAG, "onServiceDisconnected: should make null here");
            }
        }
    };

    private BroadcastReceiver mReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();

            if(info != null){
                String key = info.getString("key");

                if (key != null) {
                    switch (key){

                        case "poll":
                            Toast.makeText(getContext(), "move me somewhere", Toast.LENGTH_SHORT).show();
                            break;
                        case "no poll":
                            Toast.makeText(getContext(), "try again", Toast.LENGTH_SHORT).show();
                            mService.testValue();
                            break;
                    }
                }

            }

            Log.i(TAG, "onReceive: " + "service message received");
        }
    };

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_student_class_page_tab1_sessionlist, container, false);


        //populate list view
        // TODO: Use database to find classes that the student is enrolled
        //       Currently it is displaying a hard coded list for demonstrating purposes

        mListViewSessions = (ListView) rootView.findViewById(R.id.studentSessionListView);

        ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(
                getActivity(),
                android.R.layout.simple_list_item_1,
                sessionlistTemp);

        mListViewSessions.setAdapter(mAdapter);

        mListViewSessions.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int position, long id){
                Intent intent = new Intent(getActivity(), QuestionPage.class);
                //intent.putExtra("ClassName", mListViewSessions.getItemAtPosition(position).toString());
                Log.i(TAG, "hello from onItemClick");

                startActivity(intent);
            }
        });


        return rootView;
    }

    //-----experimentation with background work
    @Override
    public void onActivityCreated(Bundle savedInstanceState){
        super.onActivityCreated(savedInstanceState);

        //define our receivers and listeners



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
                .registerReceiver(mReceiver, new IntentFilter("ServiceMessage"));

    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    public void onResume(){
        super.onResume();

        /*if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            jobScheduler = (JobScheduler) getActivity().getSystemService(
                    Context.JOB_SCHEDULER_SERVICE);

            PersistableBundle persistableBundle = new PersistableBundle(3);
            persistableBundle.putString("class", "Tab1SessionList");

            JobInfo jobInfo = new JobInfo.Builder(4242,
                    new ComponentName(getActivity(), MyJobService.class))
                    .setOverrideDeadline(10000)
                    .setExtras(persistableBundle)
                    .build();

            jobScheduler.schedule(jobInfo);
        }*/

    }

    @Override
    public void onStop(){
        super.onStop();

        //jobScheduler = null;

        getContext().unbindService(mServiceConn);

        LocalBroadcastManager.getInstance(
                getActivity().getApplicationContext())
                .unregisterReceiver(mReceiver);
    }

}
