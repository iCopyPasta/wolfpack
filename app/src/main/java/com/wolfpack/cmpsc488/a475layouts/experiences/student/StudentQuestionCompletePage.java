package com.wolfpack.cmpsc488.a475layouts.experiences.student;

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
        //TODO: populate list with answers and highlight selected answers

        ArrayList<String> answerList = info.getStringArrayList("answerList");
//        StringBuilder sb = new StringBuilder();
//        Log.d(TAG, "answerList = ");
//        for (int i = 0; i < answerList.size(); i++) {
//            sb.append(answerList.get(i));
//            sb.append("\n");
//        }
//        Log.d(TAG, sb.toString());
//        Log.d(TAG, "done listing");

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


    private void handleQuestionTrueFalse(Bundle info){
        mRadioGroupTrueFalse.setVisibility(View.VISIBLE);
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
