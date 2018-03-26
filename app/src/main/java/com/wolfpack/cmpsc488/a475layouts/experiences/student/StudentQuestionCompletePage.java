package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.annotation.SuppressLint;
import android.graphics.drawable.ColorDrawable;
import android.support.v4.app.NavUtils;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.ArrayList;
import java.util.Locale;
import java.util.concurrent.TimeUnit;

public class StudentQuestionCompletePage extends QuestionPage {//implements ActiveSessionDialog.ActiveSessionDialogListener {
    public static final String TAG = "SQuestionCompletePage";

    private RecyclerView.LayoutManager recyclerLayoutManager;
    private AnswerSelectionRecyclerAdapter selectionAdapter;



    private String defaultQuestion = "Rick Astley's never gonna:";
    private String[] defaultAnswers = {"Give you up", "Let you down", "Make you cry", "Hurt you"};

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        Bundle bundle = getIntent().getExtras();

        try {
            sessionName = bundle.getString("sessionName");
            questionDesc = bundle.getString("questionDesc");

            questionType = bundle.getInt("questionType");

            mTextViewQuestion.setText(questionDesc);

            Log.i(TAG, "mTextViewQuestion = " + mTextViewQuestion);
            Log.i(TAG, "mTextViewQuestion text = " + mTextViewQuestion.getText().toString());


            switch (questionType){
                case QUESTION_TYPE_SELECTION:
                    handleQuestionSelection(bundle);
                    break;
                case QUESTION_TYPE_TRUE_FALSE:
                    handleQuestionTrueFalse(bundle);
                    break;
                case QUESTION_TYPE_CHOICE:
                    handleQuestionChoice(bundle);
                    break;
                default:
                    throw new RuntimeException("questionType is out of range");
            }

        }
        catch(NullPointerException e){
            Log.i(TAG, e.getMessage());
            throw e;
        }

        Log.i(TAG, "finished onCreate");

    }


    private void handleQuestionSelection(Bundle info){
        mRecyclerViewSelection.setVisibility(View.VISIBLE);
        //TODO: highlight selected answers and correct answers

        ArrayList<String> answerList = info.getStringArrayList("answerList");
        ArrayList<Integer> correctAnswers = info.getIntegerArrayList("correctAnswers");
        ArrayList<Integer> studentAnswers = info.getIntegerArrayList("studentAnswers");


        mRecyclerViewSelection.setHasFixedSize(false);
        recyclerLayoutManager = new LinearLayoutManager(this);
        mRecyclerViewSelection.setLayoutManager(recyclerLayoutManager);
        selectionAdapter = new AnswerSelectionRecyclerAdapter(answerList, getApplicationContext(), false);

        selectionAdapter.setItemClickListener(new ItemClickListener() {
            @Override
            public void onClick(View view, int position) {
                //do nothing
            }
        });

        mRecyclerViewSelection.setAdapter(selectionAdapter);

        Log.i(TAG, "finished handleQuestionSelection");
    }


    //@SuppressLint("ResourceAsColor")
    private void handleQuestionTrueFalse(Bundle info){
        mRadioGroupTrueFalse.setVisibility(View.VISIBLE);

        boolean correctAnswer = info.getBoolean("correctAnswer");
        boolean studentAnswer = info.getBoolean("studentAnswer");

        RadioButton trueButton= (RadioButton) mRadioGroupTrueFalse.getChildAt(0);
        RadioButton falseButton = (RadioButton) mRadioGroupTrueFalse.getChildAt(1);

        mRadioGroupTrueFalse.check((studentAnswer) ? trueButton.getId() : falseButton.getId());
        trueButton.setClickable(false);
        falseButton.setClickable(false);

        if (correctAnswer){
            trueButton.setBackgroundColor(getResources().getColor(R.color.colorCorrectAnswer));
            falseButton.setBackgroundColor(getResources().getColor(R.color.colorWrongAnswer));
        }
        else {
            trueButton.setBackgroundColor(getResources().getColor(R.color.colorWrongAnswer));
            falseButton.setBackgroundColor(getResources().getColor(R.color.colorCorrectAnswer));
        }

        Log.i(TAG, "true = " + trueButton+ "\nfalse = " + falseButton);


        //TODO: highlight selected answer
    }

    private void handleQuestionChoice(Bundle info){
        mRecyclerViewSelection.setVisibility(View.VISIBLE);
        //TODO: populate list with answers and highlight selected answer
    }











//    /**
//     * ActiveSessionDialog.ActiveSessionDialogListener function implementation
//     */
//
//    @Override
//    public void onPositiveClick() {
//
//    }
//
//    @Override
//    public void onNegativeClick() {
//
//    }


}
