package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.List;


public class QuestionPage extends AppCompatActivity {

    public static final String TAG = "QuestionPage";

    public static final int QUESTION_TYPE_SELECTION = 1;
    public static final int QUESTION_TYPE_TRUE_FALSE = 2;
    public static final int QUESTION_TYPE_CHOICE = 3;


    protected TextView mTextViewQuestion;
    protected RecyclerView mRecyclerViewSelection;
    protected RecyclerView mRecyclerViewChoice;
    protected RadioGroup mRadioGroupTrueFalse;

    protected String className;
    protected String sessionName;
    protected String questionDesc;
    protected int questionType;

    protected List<String> answerList;



    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_question_page);

        try {
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.colorStudentPrimary)));

            Log.i(TAG, "super class onCreate");

            mTextViewQuestion = findViewById(R.id.questionTextView);
            //mRecyclerViewChoice = findViewById(R.id.choiceRecyclerView);
            mRecyclerViewSelection = findViewById(R.id.selectionRecyclerView);
            mRadioGroupTrueFalse = findViewById(R.id.trueFalseRadioGroup);

            Log.i(TAG, "mTextViewQuestion = " + mTextViewQuestion.getText().toString());
            Log.i(TAG, "mRecyclerViewSelection = " + mRecyclerViewSelection);
            Log.i(TAG, "mRadioGroupTrueFalse= " + mRadioGroupTrueFalse);



        }
        catch (NullPointerException e){
            Log.i(TAG, e.getMessage());
            throw e;
        }




    }
}
