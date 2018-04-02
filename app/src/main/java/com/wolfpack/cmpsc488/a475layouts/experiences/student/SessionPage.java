package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.widget.TextView;

import com.wolfpack.cmpsc488.a475layouts.R;



public class SessionPage extends AppCompatActivity {

    public static final String TAG = "SessionPage";

    //UI elements
    protected TextView mTextViewSessionName;
    protected RecyclerView mRecyclerViewQuestionList;
    protected TextView mTextViewActiveQuestionNotice;

    //Information elements
    protected String className = "";
    protected String sessionName = "";


    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_session_page);

        try{
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.colorStudentPrimary)));

            Log.i(TAG, "super class onCreate");


            mTextViewSessionName = findViewById(R.id.sessionNameTextView);
            mRecyclerViewQuestionList = findViewById(R.id.questionListRecycleView);
            mTextViewActiveQuestionNotice = findViewById(R.id.activeQuestionNoticeTextView);



        }
        catch (NullPointerException e){
            Log.i(TAG, e.getMessage());
            throw e;
        }




    }
}
