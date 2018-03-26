package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.app.DialogFragment;
import android.app.FragmentManager;
import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.os.IBinder;
import android.support.v4.content.LocalBroadcastManager;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyStartedService;

import java.util.ArrayList;


// given MY_SERVICE_QUESTION_SET_ID, MY_SERVICE_QUESTION_SESSION_ID
// we ask if there is an active question here!
public class StudentSessionPage extends AppCompatActivity {

    public static final String TAG = "SSessionPage";

    private String className = "";
    private String sessionName = "";
    private String classId = null;

    private TextView mTextViewSessionName;
    private TextView mTextViewQuestionNotice;

    //activeSession refers to if there is an active session for the class (not necessarily this session)
    private boolean activeSession = true;

    //isActiveSession refers to if there the current session is STILL active
    //isActiveQuestion refers to querying the database if there is an active question
    private boolean isActiveSession = false;
    private boolean isActiveQuestion = false;

    // question set information if one is active
    private String questionSetId = null;
    private String questionSessionId = null;
    private boolean foundQuestion;

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
                if(questionSessionId != null && questionSetId != null){
                    Log.i(TAG, "onServiceConnected: myService and questionSetId and questionSessionId are not null");
                    mService.searchActiveQuestion(questionSetId, "true");

                }

            }

        }

        @Override
        public void onServiceDisconnected(ComponentName componentName) {
            if(mService != null){
                Log.i(TAG, "onServiceDisconnected: " + TAG + " disconnected from MyStartedService");
            }
        }
    };

    //receiver for an active question
    private BroadcastReceiver mReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();
            Log.i(TAG, "onReceive: " + "service message received");

            if(info != null){

                foundQuestion = true;

                //force into StudentQuestionActivePage
                Intent activeQuestionIntent = new Intent(StudentSessionPage.this,
                        StudentQuestionActivePage.class);

                activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_ID,
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_ID));

                activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_HISTORY_ID,
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_HISTORY_ID));

                activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID
                        ,questionSessionId);

                activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_SET_ID,
                        questionSetId);

                //extras to get back
                activeQuestionIntent.putExtra("classId", classId);
                activeQuestionIntent.putExtra("className", className);

                startActivity(activeQuestionIntent);

            }
            else{
                Log.i(TAG, "onReceive: " + "no active questionId for " +
                        "questionSessionId = " + questionSessionId + ", " +
                        "quesitonSetId = " + questionSetId);

                //make sure we are still alive!
                mService.searchActiveSession(classId, "false");

            }
        }
    };

    //reciever to make sure THIS session is still alive
    private BroadcastReceiver mReceiver2 = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();
            Log.i(TAG, "onReceive: " + "service message received");

            if(info != null){
                Log.i(TAG, "onReceive: " + "active poll still found for class " + classId);

                //ask for an active question!
                if(questionSessionId != null && questionSetId != null){
                    Log.i(TAG, "onReceive: " + "Looking for active question again");

                    if(!questionSessionId.equals(
                            info.getString(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID))){
                        //we have a new session!
                        Toast.makeText(StudentSessionPage.this,
                                "NEW SESSION",
                                Toast.LENGTH_SHORT).show();

                        //TODO: in the event of a "switch-a-roo", implement logic
                    }

                    if(!foundQuestion && questionSetId != null){
                        Log.i(TAG, "onReceive: " + "re-seeking active question");
                        mService.searchActiveQuestion(questionSetId, "false");
                    }
                }
            }
            else{
                Toast.makeText(StudentSessionPage.this, "Inactive", Toast.LENGTH_SHORT)
                        .show();

                Intent finishedCycle = new Intent(StudentSessionPage.this,
                        StudentClassPage.class);

                finishedCycle.putExtra("finishedCycle", true);
                finishedCycle.putExtra("classId", classId);
                finishedCycle.putExtra("className", className);
                startActivity(finishedCycle);
                finish();
            }
        }
    };


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_session_page);

        Bundle bundle = getIntent().getExtras();


        try{
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.colorStudentPrimary)));


            className = (String) bundle.get("className");
            classId = bundle.getString("classId");

            //is this THE active session?
            isActiveSession = (boolean) bundle.get("isActive");

            //get all the views
            mTextViewSessionName = findViewById(R.id.sessionNameTextView);
            mTextViewQuestionNotice = findViewById(R.id.activeQuestionNoticeTextView);
            foundQuestion = false;


            //decide how to handle it
            if (isActiveSession){
                questionSetId = bundle.getString(
                        MyStartedService.MY_SERVICE_QUESTION_SET_ID);

                questionSessionId = bundle.getString(
                        MyStartedService.MY_SERVICE_QUESTION_SESSION_ID);

                handleActiveSession();
            }
            else{
                handleCompletedSession();
            }


            Log.i(TAG, "className = "+className);
            Log.i(TAG, "sessionName = "+sessionName);

        }
        catch(NullPointerException e){
            Log.i(TAG, e.getMessage());
            throw e;
        }


    }

    @Override
    public void onStart(){
        super.onStart();

        //bind to custom service
        Intent serviceIntent = new Intent(StudentSessionPage.this , MyStartedService.class);
        startService(serviceIntent);
        bindService(serviceIntent, mServiceConn, Context.BIND_AUTO_CREATE);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(mReceiver, new IntentFilter(MyStartedService.MY_SERVICE_ACTIVE_QUESTION));

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(mReceiver2, new IntentFilter(MyStartedService.MY_SERVICE_ACTIVE_SESSION));
    }

    @Override
    public void onStop(){
        super.onStop();

        unbindService(mServiceConn);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .unregisterReceiver(mReceiver);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .unregisterReceiver(mReceiver2);
    }


    // provided we are an active session, query to see if a brand new question exists
    private void handleActiveSession(){



    }

    //if this session is completed
    private void handleCompletedSession(){


    }

    @Override
    protected void onResume() {
        super.onResume();
    }

}
