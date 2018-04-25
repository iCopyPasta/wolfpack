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
import android.text.method.ScrollingMovementMethod;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyStartedService;

public class StudentSessionActivePage extends SessionPage { //implements ActiveSessionDialog.ActiveSessionDialogListener {


    // given MY_SERVICE_QUESTION_SET_ID, MY_SERVICE_QUESTION_SESSION_ID
    // we ask if there is an active question here!
    public static final String TAG = "SSessionActivePage";
    public static final String ACTIVE_PAGE_NEW_QUESTION_ID = "ACTIVE_PAGE_NEW_QUESTION_ID";
    public static final String ACTIVE_PAGE_NEW_QUESTION_SESSION_ID= "ACTIVE_PAGE_NEW_QUESTION_SESSION_ID";
    public static final String ACTIVE_PAGE_NEW_QUESTION_HISTORY_ID= "ACTIVE_PAGE_NEW_QUESTION_HISTORY_ID";
    public static final String ACTIVE_PAGE_NEW_QUESTION_SET_ID= "ACTIVE_PAGE_NEW_QUESTION_SET_ID";

    //private String className = "";
    //private String sessionName = "";
    //private String classId = null;

    //private TextView mTextViewSessionName;
    //private TextView mTextViewQuestionNotice;

    private boolean isActiveQuestion = false;

    // question set information if one is active
    private String questionSetId = null;
    private String questionSessionId = null;
    private String newQuestionId = "";
    private String newQuestionSessionId = "";
    private String newQuestionHistoryId = "";
    private String newQuestionSetId = "";

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
                    mService.searchActiveSandQ(classId, questionSetId, "true");
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
    /*private BroadcastReceiver mReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();
            Log.i(TAG, "onReceive: " + "service message received");

            if(info != null){

                //force into StudentQuestionActivePage
                Intent activeQuestionIntent = new Intent(StudentSessionActivePage.this,
                        StudentQuestionActivePage.class);

                activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_ID,
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_ID));

                activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_HISTORY_ID,
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_HISTORY_ID));

                activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID,
                        questionSessionId);

                activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_SET_ID,
                        questionSetId);

                //extras to get back
                activeQuestionIntent.putExtra(getString(R.string.KEY_CLASS_ID), classId);
                activeQuestionIntent.putExtra(getString(R.string.KEY_CLASS_DESCRIPTION), className);
                activeQuestionIntent.putExtra(getString(R.string.KEY_SESSION_ID), sessionId);

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

    //receiver to make sure THIS session is still alive
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
                        Toast.makeText(StudentSessionActivePage.this,
                                "NEW SESSION",
                                Toast.LENGTH_SHORT).show();

                        //TODO: in the event of a "switch-a-roo", implement logic
                    }

                    Log.i(TAG, "onReceive: " + "re-seeking active question");
                    mService.searchActiveQuestion(questionSetId, "false");
                }
            }
            else{ // we are no longer the active poll
                Toast.makeText(StudentSessionActivePage.this, "Inactive", Toast.LENGTH_SHORT)
                        .show();

                finish();
            }
        }
    };*/

    private BroadcastReceiver submitAnswerReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
        if(questionSessionId != null && questionSetId != null){
            Log.i(TAG, "submitAnswer allowing restart in SessionActivePage");
            mService.searchActiveSandQ(classId, questionSetId, "true");
        }
        }
    };


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        try{
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.colorStudentPrimary)));

            Bundle bundle = getIntent().getExtras();

            //set misc (text & visibility)
            mTextViewSessionDate.setText(sessionStartDate);
            mTextViewSessionName.setMovementMethod(new ScrollingMovementMethod());
            if(sessionName != null && sessionName.length() > 50) {
                mTextViewSessionName.setMaxLines(2);
                Log.w(TAG, "HELLOOOOOO");
            }
            mTextViewSessionName.setText(sessionName);


            mListViewQuestionList.setVisibility(View.GONE);
            mTextViewActiveQuestionNotice.setVisibility(View.VISIBLE);
            //mProgressBar.setVisibility(View.VISIBLE);


            //our app was killed and should be restored
            if(savedInstanceState != null){
                onRestoreInstanceState(savedInstanceState);
            }
            else{ //grab info from our calling intent

                sessionName = bundle.getString(MyStartedService.MY_SERVICE_QUESTION_SET_NAME);
                questionSetId = bundle.getString(MyStartedService.MY_SERVICE_QUESTION_SET_ID);
                sessionId = questionSessionId = bundle.getString(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID);

                //is this THE active session?

                mTextViewSessionName.setText(sessionName);
            }

            Log.i(TAG, "className = "+className);
            Log.i(TAG, "sessionName = "+sessionName);

        }
        catch(NullPointerException e){
            Log.i(TAG, e.getMessage());
            throw e;
        }

    }

    private BroadcastReceiver combinationQuery = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();
            //Log.i(TAG, "onReceive: combinationQuery" );

            if(info != null) {
                newQuestionId =
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_ID, newQuestionId);
                newQuestionSessionId =
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID, newQuestionSessionId);
                newQuestionHistoryId =
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_HISTORY_ID, newQuestionHistoryId);
                newQuestionSetId =
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_SET_ID, newQuestionSetId);

                Log.i(TAG, "onReceive: NEW QuestionId: " + newQuestionId);
                Log.i(TAG, "onReceive: NEW QuestionHistoryId: " + newQuestionHistoryId);
                Log.i(TAG, "onReceive: QuestionSessionId: " + questionSessionId + " versus " + newQuestionSessionId);
                Log.i(TAG, "onReceive: QuestionSetId: " + questionSetId + " versus " + newQuestionSetId);

                //the same exact session that we believe to exist is still present
                if(questionSessionId.equals(newQuestionSessionId)
                        && questionSetId.equals(newQuestionSetId)){

                    //we have a new question!
                    if(!newQuestionId.equals("") && !newQuestionHistoryId.equals("")){
                        //TODO: new question code
                        Log.i(TAG, "onReceive: WE HAVE A NEW QUESTION");

                        Intent activeQuestionIntent = new Intent(StudentSessionActivePage.this,
                                StudentQuestionActivePage.class);

                        activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_ID,
                                newQuestionId);

                        activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_HISTORY_ID,
                                newQuestionHistoryId);

                        activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID,
                                newQuestionSessionId);

                        activeQuestionIntent.putExtra(MyStartedService.MY_SERVICE_QUESTION_SET_ID,
                                newQuestionSetId);

                        //extras to get back
                        activeQuestionIntent.putExtra(getString(R.string.KEY_CLASS_ID), classId);
                        activeQuestionIntent.putExtra(getString(R.string.KEY_CLASS_DESCRIPTION), className);
                        activeQuestionIntent.putExtra(getString(R.string.KEY_SESSION_ID), sessionId);

                        startActivity(activeQuestionIntent);

                    } else{ //keep trying to ask for a new question
                        mService.searchActiveSandQ(classId, questionSetId, "false");
                    }
                } else{
                    //session expired as we don't have equal values
                    Toast.makeText(context, "Expired Session", Toast.LENGTH_SHORT).show();
                    finish();
                }

            }else{
                Log.w(TAG, "onReceive: info was null :(" );
                finish();
            }

        }
    };

    @Override
    protected void loadQuestionList() { }

    @Override
    public void onStart(){
        super.onStart();
    }

    @Override
    public void onSaveInstanceState(Bundle outState){
        super.onSaveInstanceState(outState);

        outState.putString(MyStartedService.MY_SERVICE_QUESTION_SET_NAME, sessionName);
        outState.putString(MyStartedService.MY_SERVICE_QUESTION_SET_ID, questionSetId);
        outState.putString(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID, questionSessionId);
        outState.putString(ACTIVE_PAGE_NEW_QUESTION_HISTORY_ID, newQuestionHistoryId);
        outState.putString(ACTIVE_PAGE_NEW_QUESTION_SESSION_ID, newQuestionSessionId);
        outState.putString(ACTIVE_PAGE_NEW_QUESTION_ID, newQuestionId);
        outState.putString(ACTIVE_PAGE_NEW_QUESTION_SET_ID, newQuestionSetId);

        outState.putString("className", className);
        outState.putString("classId", classId);
        outState.putBoolean("isActive",isActiveQuestion);

    }

    @Override
    public void onRestoreInstanceState(Bundle inState){
        super.onRestoreInstanceState(inState);

        sessionName = inState.getString(MyStartedService.MY_SERVICE_QUESTION_SET_NAME,"");
        questionSetId = inState.getString(MyStartedService.MY_SERVICE_QUESTION_SET_ID);
        questionSessionId = inState.getString(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID);
        classId = inState.getString("classId");
        className = inState.getString("className");
        isActiveQuestion = inState.getBoolean("isActive");

        newQuestionSetId = inState.getString(ACTIVE_PAGE_NEW_QUESTION_SET_ID, "");
        newQuestionHistoryId = inState.getString(ACTIVE_PAGE_NEW_QUESTION_HISTORY_ID, "");
        newQuestionSessionId = inState.getString(ACTIVE_PAGE_NEW_QUESTION_SESSION_ID, "");
        newQuestionId = inState.getString(ACTIVE_PAGE_NEW_QUESTION_ID, "");

    }

    @Override
    public void onStop(){
        super.onStop();
    }

    @Override
    public void onPause(){
        super.onPause();

        Log.i(TAG, "ON PAUSE, UNBINDING FROM SERVICE AND RECEIVERS");

        unbindService(mServiceConn);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .unregisterReceiver(submitAnswerReceiver);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .unregisterReceiver(combinationQuery);
    }

    @Override
    protected void onResume() {

        super.onResume();

        //bind to custom service
        Intent serviceIntent = new Intent(StudentSessionActivePage.this , MyStartedService.class);
        startService(serviceIntent);
        bindService(serviceIntent, mServiceConn, Context.BIND_AUTO_CREATE);

        /*LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(mReceiver, new IntentFilter(MyStartedService.MY_SERVICE_ACTIVE_QUESTION));

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(mReceiver2, new IntentFilter(MyStartedService.MY_SERVICE_ACTIVE_SESSION));*/


        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(submitAnswerReceiver,  new IntentFilter(MyStartedService.MY_SERVICE_SUBMIT_ANSWER)
                );

        LocalBroadcastManager.getInstance(
                getApplicationContext())
        .registerReceiver(combinationQuery, new IntentFilter(MyStartedService.MY_SERVICE_VALIDATE_COMBO));


        mTextViewSessionName.setText(sessionName);

    }



}
