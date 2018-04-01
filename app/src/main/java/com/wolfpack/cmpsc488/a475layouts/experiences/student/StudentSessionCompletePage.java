package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;


public class StudentSessionCompletePage extends SessionPage {


    public static final String TAG = "SSeshCmplt";

    //private String className = "";
    //private String sessionName = "";

    //private TextView mTextViewSessionName;
    //private TextView mTextViewQuestionNotice;

    //private RecyclerView mRecyclerViewQuestion;
    private RecyclerView.LayoutManager recyclerLayoutManager;

    private String[] questionlistTemp = {"What does that say?", "How are you doing today?"};
    private String[] answerlistTemp = {"Wrong answer 1", "Wrong answer 2", "Mutex", "Wrong answer 3", "answer x", "answer 17", "answer z", "answer y", "answer 42", "don’t let this man distract you from the fact that in 1998,", "The Undertaker threw Mankind off the Hell in the Cell", "and plummeted 16ft into the announcers table", "my name is what?", "my name is who?", "slim shady"};
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
//            mTextViewSessionName = findViewById(R.id.sessionNameTextView);
//            mTextViewActiveQuestionNotice = findViewById(R.id.activeQuestionNoticeTextView);
//            mRecyclerViewQuestionList = findViewById(R.id.questionListRecycleView);

            //set misc
            mTextViewSessionName.setText(sessionName);
            mTextViewActiveQuestionNotice.setVisibility(View.GONE);
            mRecyclerViewQuestionList.setVisibility(View.VISIBLE);

            //setup recycler view
            mRecyclerViewQuestionList.setHasFixedSize(false);
            recyclerLayoutManager = new LinearLayoutManager(this);
            mRecyclerViewQuestionList.setLayoutManager(recyclerLayoutManager);
            adapter = new QuestionRecyclerAdapter(Arrays.asList(questionlistTemp), this);
            adapter.setItemChoiceClickListener(new ItemChoiceClickListener() {
                        @Override
                        public void onClick(View view, int position) {
                            Intent intent = new Intent(getApplicationContext(), StudentQuestionCompletePage.class);
                            intent.putExtra("sessionName", sessionName);
                            intent.putExtra("questionDesc", adapter.getItem(position));


//                            if (position == 0) {
//                                intent.putExtra("questionType", QuestionPage.QUESTION_TYPE_CHOICE);
//                                intent.putExtra("answerList", new ArrayList<>(Arrays.asList(answerlistTemp)));
//                                Integer[] correctAns = {9, 10, 11};
//                                intent.putExtra("correctAnswers", new ArrayList<>(Arrays.asList(correctAns)));
//                                intent.putExtra("studentAnswer", 9);
//                            }
                            if (position == 0) {
                                intent.putExtra("questionType", QuestionPage.QUESTION_TYPE_CHOICE);
                                intent.putExtra("answerList", new ArrayList<>(Arrays.asList(answerlistTemp)));
                                Integer[] correctAns = {2, 8, 9, 10, 11, 14};
                                Integer[] studentAns = {2, 9, 10, 11};
                                intent.putExtra("correctAnswers", new ArrayList<>(Arrays.asList(correctAns)));
                                intent.putExtra("studentAnswers", new ArrayList<>(Arrays.asList(studentAns)));
                            }
                            else if (position == 1){
                                intent.putExtra("questionType", QuestionPage.QUESTION_TYPE_TRUE_FALSE);
                                intent.putExtra("correctAnswer", false);
                                intent.putExtra("studentAnswer", false);
                            }

                            startActivity(intent);
                        }
                    });

            mRecyclerViewQuestionList.setAdapter(adapter);


        }
        catch (NullPointerException e){
            Log.i(TAG, e.toString());
            throw e;
        }


    }









}
