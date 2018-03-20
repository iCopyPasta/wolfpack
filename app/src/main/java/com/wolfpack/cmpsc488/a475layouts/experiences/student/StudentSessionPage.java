package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.app.DialogFragment;
import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.ArrayList;

public class StudentSessionPage extends AppCompatActivity implements ActiveSessionDialog.ActiveSessionDialogListener {

    public static final String TAG = "SSessionCompletePage";

    private String className = "";
    private String sessionName = "";

    private TextView mTextViewSessionName;
    private TextView mTextViewQuestionNotice;

    private RecyclerView mRecyclerViewQuestions;

    //private String[] questionlistTemp = {}
    private int[] questionlistTemp = {1000,2934, 2882, 1111, 1939};

    //activeSession refers to if there is an active session for the class (not necessarily this session)
    private boolean activeSession = true;

    //isActiveSession refers to if there the current session is STILL active
    //isActiveQuestion refers to querying the database if there is an active question
    private boolean isActiveSession = false;
    private boolean isActiveQuestion = false;


    private Bundle bundle;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_session_page);

        bundle = getIntent().getExtras();

        try{
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.colorStudentPrimary)));


            className = (String) bundle.get("className");

            //is this THE active session?
            isActiveSession = (boolean) bundle.get("isActive");

            //get all the views
            mTextViewSessionName = findViewById(R.id.sessionNameTextView);
            mTextViewQuestionNotice = findViewById(R.id.activeQuestionNoticeTextView);

            //TODO: add recycler view
            //mRecyclerViewQuestions = findViewById(R.id.questionListRecycleView):


            //decide how to handle it
            if (isActiveSession){
                handleActiveSession();
            }
            else{
                handleCompletedSession();
            }


            Log.i(TAG, "className = "+className);
            Log.i(TAG, "sessionName = "+sessionName);

        }
        catch(NullPointerException e){
            Log.i(TAG, e.toString());
            throw e;
        }


    }


    //if this session is active
    private void handleActiveSession(){
        //TODO: query for the session name (or maybe done by caller)

        //TODO: start an async task to query if the session is still alive
        //TODO: start an async task to query if a question is live

    }

    //if this session is completed
    private void handleCompletedSession(){
        mTextViewQuestionNotice.setVisibility(View.GONE);

        sessionName = (String) bundle.get("sessionName");

        mTextViewSessionName.setText(sessionName);

        //TODO: start an async task to query if there is an active session

        //TODO: populate a list (recycle view) here


    }



    //temporary code to access question page
    public void gotoQuestionPage(View view){
        String str = ((Button) view).getText().toString();
        int pos = 0;
        switch(str){
            case "question1": pos = 1; break;
            case "question2": pos = 2; break;
            case "question3": pos = 3; break;
            case "question4": pos = 4; break;
            case "question5": pos = 5; break;
        }

        Intent intent = new Intent(this, StudentQuestionCompletePage.class);
        intent.putExtra("className", className);
        intent.putExtra("sessionName", sessionName);
        intent.putExtra("isActive", false);
        intent.putExtra("questionId", questionlistTemp[pos]);

        startActivity(intent);

    }



    //TODO: move this to an async task (created in handleCompletedSession)
    @Override
    protected void onResume() {
        super.onResume();

        //checking if there is an active session, but the active session is not this active session
        if (activeSession && !isActiveSession) {
            DialogFragment dialogFragment = new ActiveSessionDialog();
            dialogFragment.show(getFragmentManager(), "SessionActive");
        }

    }



    /**
     * ActiveSessionDialog.ActiveSessionDialogListener function implementation
     */

    @Override
    public void onPositiveClick() {
        //send user to an active session's page
        Intent intent = new Intent(getApplicationContext(), StudentSessionPage.class);
        intent.putExtra("className", className);
        //TODO: decide who gets the session name
        //intent.putExtra("sessionName", "");
        intent.putExtra("isActive", true);
        startActivity(intent);
        finish();
    }

    @Override
    public void onNegativeClick() {
        //nothing happens
    }
}
