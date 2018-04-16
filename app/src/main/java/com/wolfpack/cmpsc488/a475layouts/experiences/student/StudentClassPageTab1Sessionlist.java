package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.annotation.SuppressLint;
import android.app.AlertDialog;
import android.app.DialogFragment;
import android.app.FragmentManager;
import android.app.job.JobInfo;
import android.app.job.JobScheduler;
import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.ContentValues;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.database.Cursor;
import android.database.sqlite.SQLiteConstraintException;
import android.database.sqlite.SQLiteDatabase;
import android.os.AsyncTask;
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
import android.widget.ProgressBar;
import android.widget.SimpleAdapter;
import android.widget.SimpleCursorAdapter;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyJobService;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyStartedService;
import com.wolfpack.cmpsc488.a475layouts.services.sqlite_database.PollatoDB;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

// given our class id, we ask if there is an active session here!
public class StudentClassPageTab1Sessionlist extends Fragment {

    private static final String TAG = "TCPTab1Sessionlist";

    private StudentClassPage activity;

    private String classId;
    private String className;

    private SQLiteDatabase db;
    private ListView mListViewSessions;
    private ProgressBar mProgressBar;
    private SimpleCursorAdapter adapter;

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

                ActiveSessionDialog activeSessionDialog = new ActiveSessionDialog();
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
        View rootView = inflater.inflate(R.layout.fragment_student_class_page_tab1_sessionlist, container, false);

        try {
            activity = (StudentClassPage) getActivity();
            classId = activity.getClassId();
            className = activity.getClassName();

            mListViewSessions = rootView.findViewById(R.id.studentSessionListView);
            mProgressBar = rootView.findViewById(R.id.studentPageProgressBar);
            //mProgressBar.setVisibility(View.VISIBLE);

            setupListAdapter();
            setupListView();

            //get writable database
            PollatoDB.getInstance(activity).getWritableDatabase(
                    new PollatoDB.OnDBReadyListener() {
                        @Override
                        public void onDBReady(SQLiteDatabase db) {
                            StudentClassPageTab1Sessionlist.this.db = db;
                            loadSessionList();
                        }
                    }
            );

        }
        catch (NullPointerException e){
            Log.i(TAG, e.toString());
            throw e;
        }

        return rootView;
    }



    private void setupListAdapter(){
        adapter = new SimpleCursorAdapter(getContext(),
                R.layout.listview_session_list,
                null,
                new String[] {"name", "start_date"},
                new int[] {R.id.sessionNameTextView, R.id.sessionDateTextView},
                0);

    }


    private void setupListView(){
        mListViewSessions.setAdapter(adapter);

        mListViewSessions.setOnItemClickListener(new AdapterView.OnItemClickListener(){
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id){
                Cursor c = adapter.getCursor();

                if (c.moveToPosition(position)) {
                    Intent intent = new Intent(activity, StudentSessionCompletePage.class);

                    intent.putExtra(getString(R.string.KEY_CLASS_ID), classId);
                    intent.putExtra(getString(R.string.KEY_CLASS_TITLE), className);

                    intent.putExtra(getString(R.string.KEY_SESSION_ID), String.valueOf(c.getInt(0)));
                    intent.putExtra(getString(R.string.KEY_SESSION_NAME), c.getString(1));
                    intent.putExtra(getString(R.string.KEY_SESSION_START_DATE), c.getString(2));

                    startActivity(intent);
                }
            }
        });

    }





    @Override
    public void onActivityCreated(Bundle savedInstanceState){
        super.onActivityCreated(savedInstanceState);
    }

    @Override
    public void onStart(){
        super.onStart();

    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    public void onResume(){
        super.onResume();

        if (db != null)
            loadSessionList();

        //bind to custom service
        Intent serviceIntent = new Intent(getContext() , MyStartedService.class);
        getContext().startService(serviceIntent);
        getContext().bindService(serviceIntent, mServiceConn, Context.BIND_AUTO_CREATE);

        LocalBroadcastManager.getInstance(
                activity.getApplicationContext())
                .registerReceiver(mReceiver, new IntentFilter(MyStartedService.MY_SERVICE_ACTIVE_SESSION));

    }

    @Override
    public void onStop(){
        super.onStop();

        getContext().unbindService(mServiceConn);

        LocalBroadcastManager.getInstance(
                activity.getApplicationContext())
                .unregisterReceiver(mReceiver);
    }




    @SuppressLint("StaticFieldLeak")
    public void loadSessionList(){
        new AsyncTask<Void, Void, Cursor>(){

            @Override
            protected void onPreExecute(){
                Log.w(TAG, "is this being called????");
                mProgressBar.setVisibility(View.VISIBLE);
            }

            @Override
            protected Cursor doInBackground(Void... params) {
                String[] projection = {"_id", "name", "start_date"};
                String table = getString(R.string.TABLE_SESSION);
                String selection = "class_id = ?";
                String[] selectionArgs = {String.valueOf(classId)};
                String sortOrder = "_id DESC";

                Log.w(TAG, "we are here my fuzzy friend");

                PollatoDB.printDatabase();

                return db.query(
                        table,
                        projection,
                        selection,
                        selectionArgs,
                        null,
                        null,
                        sortOrder
                );
            }

            @Override
            protected void onPostExecute(Cursor cursor) {
                adapter.swapCursor(cursor);
                mProgressBar.setVisibility(View.GONE);
            }

        }.execute();
    }




    @SuppressLint("StaticFieldLeak")
    public void addSession(final String sessionId, final String sessionName){
        new AsyncTask<Void, Void, Boolean>(){

            @Override
            protected Boolean doInBackground(Void... params) {
                int _id = Integer.parseInt(sessionId);
                int _class_id = Integer.parseInt(classId);

                String _date = new SimpleDateFormat("MM-dd-yyyy, hh:mm", Locale.US)
                        .format(Calendar.getInstance().getTime());

                String table = getString(R.string.TABLE_SESSION);

                ContentValues values = new ContentValues();
                values.put("_id", _id);
                values.put("class_id", _class_id);
                values.put("name", sessionName);
                values.put("start_date", _date);

                long result = 0;
                try {
                    result = db.insert(table, null, values);
                }
                catch (SQLiteConstraintException e){
                    Log.i(TAG, "row already exists: result = " + result);
                    Log.d(TAG, "message: " + e.getMessage());
                    result = 1;
                }

                return result == -1;
            }

            @Override
            protected void onPostExecute(Boolean wasInserted) {
                if (wasInserted) loadSessionList();
            }

        }.execute();

    }










}
