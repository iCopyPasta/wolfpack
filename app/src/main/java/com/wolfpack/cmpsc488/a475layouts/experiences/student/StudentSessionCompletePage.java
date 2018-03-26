package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.content.Context;
import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.widget.TextView;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;


public class StudentSessionCompletePage extends AppCompatActivity {


    public static final String TAG = "SSeshCmplt";

    private String className = "";
    private String sessionName = "";

    private TextView mTextViewSessionName;
    private TextView mTextViewQuestionNotice;

    private RecyclerView mRecyclerViewQuestion;
    private RecyclerView.LayoutManager recyclerLayoutManager;

    private String[] questionlistTemp = {"What does that say?", "How are you doing today?"};
    private List<String> questionlistTempList = new ArrayList<String>();

    private QuestionRecyclerAdapter adapter;


    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_session_page);


        try{
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.colorStudentPrimary)));

            Bundle bundle = getIntent().getExtras();

            className = bundle.getString("className");
            sessionName = bundle.getString("sessionName");

            //get all the view
            mTextViewSessionName = findViewById(R.id.sessionNameTextView);
            mTextViewQuestionNotice = findViewById(R.id.activeQuestionNoticeTextView);
            mRecyclerViewQuestion = findViewById(R.id.questionListRecycleView);

            mTextViewSessionName.setText(sessionName);
            mTextViewQuestionNotice.setVisibility(View.GONE);
            mRecyclerViewQuestion.setVisibility(View.VISIBLE);

            mRecyclerViewQuestion.setHasFixedSize(false);
            recyclerLayoutManager = new LinearLayoutManager(this);
            mRecyclerViewQuestion.setLayoutManager(recyclerLayoutManager);
            adapter = new QuestionRecyclerAdapter(Arrays.asList(questionlistTemp), this);
            adapter.setItemClickListener(new ItemClickListener() {
                        @Override
                        public void onClick(View view, int position) {
                            Intent intent = new Intent(getApplicationContext(), StudentQuestionCompletePage.class);
                            intent.putExtra("sessionName", sessionName);
                            intent.putExtra("questionDesc", adapter.getItem(position));
                            startActivity(intent);
                        }
                    });

            mRecyclerViewQuestion.setAdapter(adapter);


        }
        catch (NullPointerException e){
            Log.i(TAG, e.toString());
            throw e;
        }


    }









}
