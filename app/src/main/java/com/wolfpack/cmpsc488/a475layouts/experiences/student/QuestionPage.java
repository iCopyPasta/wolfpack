package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.database.sqlite.SQLiteDatabase;
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
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.QuestionInformation;
import com.wolfpack.cmpsc488.a475layouts.services.sqlite_database.PollatoDB;

import java.util.ArrayList;
import java.util.List;


public abstract class QuestionPage extends AppCompatActivity {

    public static final String TAG = "QuestionPage";

    //database
    protected SQLiteDatabase db;

    //ui elements
    protected TextView mTextViewQuestion;
    protected RecyclerView mRecyclerViewChoice;
    protected RadioGroup mRadioGroupTrueFalse;
    //protected RadioGroup mRadioGroupChoiceOLD;
    //protected RecyclerView mRecyclerViewChoiceOLD;

    protected RecyclerView.LayoutManager recyclerLayoutManager;
    protected AnswerChoiceRecyclerAdapter choiceAdapter;

    //information
    protected String studentId = "";
    protected String classId = "";
    protected String className = "";

    protected String sessionId = "";
    protected String sessionName = "";
    protected String sessionStartDate = "";

    protected String questionId = "";
    protected String questionDesc = "";
    protected String questionType = "";
    protected String questionPotentialAnswers = "";
    protected String questionCorrectAnswers = "";
    protected String questionStudentAnswers = "";

    protected QuestionInformation questionInformation;

    protected ArrayList<String> potentialAnswerList = null;
    protected ArrayList<Integer> correctAnswerList = null;
    protected ArrayList<Integer> studentAnswerList = null;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_question_page);

        try {
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.colorStudentPrimary)));

            Log.i(TAG, "super class onCreate");

            mTextViewQuestion = findViewById(R.id.questionTextView);
            mRecyclerViewChoice = findViewById(R.id.choiceRecyclerView);
            mRadioGroupTrueFalse = findViewById(R.id.trueFalseRadioGroup);
            //mRadioGroupChoiceOLD = findViewById(R.id.choiceRadioGroup);
            //mRecyclerViewChoiceOLD = findViewById(R.id.choiceRecyclerView);

            Log.i(TAG, "mTextViewQuestion = " + mTextViewQuestion.getText().toString());
            Log.i(TAG, "mRecyclerViewChoice = " + mRecyclerViewChoice);
            Log.i(TAG, "mRadioGroupTrueFalse= " + mRadioGroupTrueFalse);

            PollatoDB.getInstance(this).getWritableDatabase(
                    new PollatoDB.OnDBReadyListener() {
                        @Override
                        public void onDBReady(SQLiteDatabase db) {
                            QuestionPage.this.db = db;
                        }
                    }
            );

        }
        catch (NullPointerException e){
            Log.i(TAG, e.getMessage());
            throw e;
        }




    }

    protected abstract void handleQuestionChoice(QuestionInformation info);
    protected abstract void handleQuestionTrueFalse(QuestionInformation info);


}
