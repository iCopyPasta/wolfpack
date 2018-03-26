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
    private AnswerChoiceRecyclerAdapter choiceAdapter;


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
                //TODO: must look for solution to problem (posted below)
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

    /**
     * handleQuestionSelection handle the case that the question being displayed is a selection question
     * Bundle must hold the following:
     *     "answerList" : List<String> (a list of possible answers)
     *     "correctAnswers" : List<Integer> (a list of indices into the answerList that are correct answers)
     *     "studentAnswers" : List<Integer> (a list of indices into the answerList that were the student answers)
     * @param info
     */
    private void handleQuestionSelection(Bundle info){
        mRecyclerViewSelection.setVisibility(View.VISIBLE);

        ArrayList<String> answerList = info.getStringArrayList("answerList");
        ArrayList<Integer> correctAnswers = info.getIntegerArrayList("correctAnswers");
        ArrayList<Integer> studentAnswers = info.getIntegerArrayList("studentAnswers");


        mRecyclerViewSelection.setHasFixedSize(false);
        recyclerLayoutManager = new LinearLayoutManager(this);
        mRecyclerViewSelection.setLayoutManager(recyclerLayoutManager);
        selectionAdapter = new AnswerSelectionRecyclerAdapter(getApplicationContext(), answerList, correctAnswers, studentAnswers, false);

        selectionAdapter.setItemClickListener(new ItemClickListener() {
            @Override
            public void onClick(View view, int position) {
                //do nothing
            }
        });

        mRecyclerViewSelection.setAdapter(selectionAdapter);

        Log.i(TAG, "finished handleQuestionSelection");
    }


    /**
     * handleQuestionTrueFalse handle the case that the question being displayed is a true false question
     * Bundle must hold the following:
     *     "correctAnswers" : Boolean (the correct answer)
     *     "studentAnswers" : Boolean (the student's answer)
     * @param info
     */
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
        Log.i(TAG, "finished handleQuestionTrueFalse");
    }



    /**
     * handleQuestionSelection handle the case that the question being displayed is a selection question
     * Bundle must hold the following:
     *     "answerList" : List<String> (a list of possible answers)
     *     "correctAnswers" : List<Integer> (a list of indices into the answerList that are correct answers)
     *     "studentAnswers" : Integer (an indices into the answerList that was the student answers)
     * @param info
     */
    //TODO: Choice questions: radio buttons not working, will need to create own version OR
    //TODO:     find a way to dynamically add more radio buttons to a radio group
    private void handleQuestionChoice(Bundle info){
        mRadioGroupChoice.setVisibility(View.VISIBLE);
        mRecyclerViewChoice.setVisibility(View.VISIBLE);

        ArrayList<String> answerList = info.getStringArrayList("answerList");
        ArrayList<Integer> correctAnswers = info.getIntegerArrayList("correctAnswers");
        int studentAnswer = info.getInt("studentAnswer");

//        Log.i(TAG, "correctAnswers = ");
//        StringBuilder sb = new StringBuilder();
//        for (int i = 0; i < correctAnswers.size(); i++){
//            sb.append(correctAnswers.get(i));
//            sb.append("\n");
//        }
//        Log.i(TAG, sb.toString());
//        Log.i(TAG, "studentAnswer = " + studentAnswer);


        mRecyclerViewChoice.setHasFixedSize(false);
        recyclerLayoutManager = new LinearLayoutManager(this);
        mRecyclerViewChoice.setLayoutManager(recyclerLayoutManager);
        choiceAdapter = new AnswerChoiceRecyclerAdapter(getApplicationContext(), answerList, correctAnswers, studentAnswer, true);

        choiceAdapter.setItemClickListener(new ItemClickListener() {
            @Override
            public void onClick(View view, int position) {
                //do nothing
            }
        });

        mRecyclerViewChoice.setAdapter(choiceAdapter);

        Log.i(TAG, "finished handleQuestionChoice");
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
