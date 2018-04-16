package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.annotation.SuppressLint;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.widget.RadioButton;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.QuestionInformation;

import java.util.ArrayList;
import java.util.Arrays;

public class StudentQuestionCompletePage extends QuestionPage {
    public static final String TAG = "SQuestionCompletePage";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        try {
            Bundle bundle = getIntent().getExtras();
            studentId = bundle.getString(getString(R.string.KEY_STUDENT_ID));
            classId = bundle.getString(getString(R.string.KEY_CLASS_ID));
            className = bundle.getString(getString(R.string.KEY_CLASS_TITLE));

            sessionId = bundle.getString(getString(R.string.KEY_SESSION_ID));
            sessionName = bundle.getString(getString(R.string.KEY_SESSION_NAME));
            sessionStartDate = bundle.getString(getString(R.string.KEY_SESSION_START_DATE));

            questionId = bundle.getString(getString(R.string.KEY_QUESTION_ID));
            questionDesc = bundle.getString(getString(R.string.KEY_QUESTION_DESCRIPTION));
            questionType = bundle.getString(getString(R.string.KEY_QUESTION_TYPE));
            questionPotentialAnswers = bundle.getString(getString(R.string.KEY_QUESTION_POTENTIAL_ANSWERS));
            questionCorrectAnswers = bundle.getString(getString(R.string.KEY_QUESTION_CORRECT_ANSWERS));
            questionStudentAnswers = bundle.getString(getString(R.string.KEY_QUESTION_STUDENT_ANSWERS));

            QuestionInformation info = new QuestionInformation();
            info.setQuestionId(questionId);
            info.setDescription(questionDesc);
            info.setQuestionType(questionType);
            info.setPotentialAnswers(questionPotentialAnswers);
            info.setCorrectAnswers(questionCorrectAnswers);

            handleCompleteQuestion(info, questionStudentAnswers);
        }
        catch(NullPointerException e){
            Log.i(TAG, e.getMessage());
            throw e;
        }

        Log.i(TAG, "finished onCreate");

    }



    protected void handleCompleteQuestion(QuestionInformation info, String questionStudentAnswer){
        questionDesc = info.getDescription();
        mTextViewQuestion.setText(info.getDescription());

        Log.i("handleCompleteQuestion", "question id = " + info.getQuestionId() + "\n" +
                "question desc = " + info.getDescription() + "\n" +
                "question type = " + info.getQuestionType() + "\n" +
                "potential answers = " + info.getPotentialAnswers() + "\n" +
                "correct answers = " + info.getCorrectAnswers() + "\n" +
                "questionStudentAnswer = " + questionStudentAnswer);

        if (info.getQuestionType().equals(getString(R.string.QUESTION_TYPE_TRUE_FALSE))){
            handleQuestionTrueFalse(info, questionStudentAnswer);
        }
        else if (info.getQuestionType().equals(getString(R.string.QUESTION_TYPE_CHOICE))){
            handleQuestionChoice(info, questionStudentAnswer);
        }

    }


    protected void handleQuestionChoice(QuestionInformation info, String questionStudentAnswer){
        mRecyclerViewChoice.setVisibility(View.VISIBLE);

        //getting potential answers
        String answerString = info.getPotentialAnswers();
        answerString = answerString.substring(2, answerString.length() - 2);
        potentialAnswerList = new ArrayList<>(Arrays.asList(answerString.split("\",\"")));

        //getting correct answers
        String correctString = info.getCorrectAnswers();
        correctString = correctString.substring(2, correctString.length() - 2);
        correctAnswerList = new ArrayList<>();
        for (String s : correctString.split("\",\"")){
            correctAnswerList.add(Integer.parseInt(s) - 1);
        }

        //getting student answers
        String studentString = questionStudentAnswer.substring(2, questionStudentAnswer.length() - 2);
        studentAnswerList = new ArrayList<>();
        for (String s : studentString.split("\",\"")){
            studentAnswerList.add(Integer.parseInt(s));
        }

        Log.i("handleActiveQuestion", "teacher id = " + info.getTeacherId() + "\n" +
                "question id = " + info.getQuestionId() + "\n" +
                "question desc = " + info.getDescription() + "\n" +
                "question type = " + info.getQuestionType() + "\n" +
                "potential answers = " + potentialAnswerList + "\n" +
                "correct answers = " + correctAnswerList + "\n" +
                "student answers = " + studentAnswerList + "\n\n");


        mRecyclerViewChoice.setHasFixedSize(false);
        recyclerLayoutManager = new LinearLayoutManager(this);
        mRecyclerViewChoice.setLayoutManager(recyclerLayoutManager);
        choiceAdapter = new AnswerChoiceRecyclerAdapter(this,
                potentialAnswerList,
                correctAnswerList,
                studentAnswerList,
                false);

        mRecyclerViewChoice.setAdapter(choiceAdapter);

        Log.i(TAG, "finished handleQuestionChoice");
    }


    protected void handleQuestionTrueFalse(QuestionInformation info, String questionStudentAnswer) {
        mRadioGroupTrueFalse.setVisibility(View.VISIBLE);

        boolean correctAnswer = info.getCorrectAnswers().equals("[\"1\"]");
        boolean studentAnswer = questionStudentAnswer.equals("[\"1\"]");

        RadioButton trueButton= (RadioButton) mRadioGroupTrueFalse.getChildAt(0);
        RadioButton falseButton = (RadioButton) mRadioGroupTrueFalse.getChildAt(1);

        mRadioGroupTrueFalse.check((studentAnswer) ? trueButton.getId() : falseButton.getId());
        trueButton.setClickable(false);
        falseButton.setClickable(false);

        trueButton.setTextColor(
                (correctAnswer) ? getResources().getColor(R.color.colorCorrectAnswer)
                        : getResources().getColor(R.color.colorWrongAnswer));

        falseButton.setTextColor(
                (!correctAnswer) ? getResources().getColor(R.color.colorCorrectAnswer)
                        : getResources().getColor(R.color.colorWrongAnswer));


        trueButton.setChecked(studentAnswer);
        falseButton.setChecked(!studentAnswer);

        Log.i(TAG, "true = " + trueButton+ "\nfalse = " + falseButton);
        Log.i(TAG, "finished handleQuestionTrueFalse");

    }



    /**
     * handleQuestionSelection handle the case that the question being displayed is a selection question
     * Bundle must hold the following:
     *     "answerList" : List<String> (a list of possible answers)
     *     "correctAnswers" : List<Integer> (a list of indices into the answerList that are correct answers)
     *     "studentAnswers" : List<Integer> (a list of indices into the answerList that were the student answers)
     * @param info
     */
    protected void handleQuestionChoice(QuestionInformation info){
//        mRecyclerViewChoice.setVisibility(View.VISIBLE);
//
//        ArrayList<String> answerList = info.getStringArrayList("answerList");
//        ArrayList<Integer> correctAnswers = info.getIntegerArrayList("correctAnswers");
//        ArrayList<Integer> studentAnswers = info.getIntegerArrayList("studentAnswers");
//
//
//        mRecyclerViewChoice.setHasFixedSize(false);
//        recyclerLayoutManager = new LinearLayoutManager(this);
//        mRecyclerViewChoice.setLayoutManager(recyclerLayoutManager);
//        choiceAdapter = new AnswerChoiceRecyclerAdapter(getApplicationContext(), answerList, correctAnswers, studentAnswers, false);
//
//        choiceAdapter.setItemChoiceClickListener(new ItemChoiceClickListener() {
//            @Override
//            public void onClick(View view, int position) {
//                //do nothing
//            }
//        });
//
//        mRecyclerViewChoice.setAdapter(choiceAdapter);
//
//        Log.i(TAG, "finished handleQuestionChoice");
    }


    /**
     * handleQuestionTrueFalse handle the case that the question being displayed is a true false question
     * Bundle must hold the following:
     *     "correctAnswers" : Boolean (the correct answer)
     *     "studentAnswers" : Boolean (the student's answer)
     * @param info
     */
    protected void handleQuestionTrueFalse(QuestionInformation info){
//        mRadioGroupTrueFalse.setVisibility(View.VISIBLE);
//
//        boolean correctAnswer = info.getBoolean("correctAnswer");
//        boolean studentAnswer = info.getBoolean("studentAnswer");
//
//        RadioButton trueButton= (RadioButton) mRadioGroupTrueFalse.getChildAt(0);
//        RadioButton falseButton = (RadioButton) mRadioGroupTrueFalse.getChildAt(1);
//
//        mRadioGroupTrueFalse.check((studentAnswer) ? trueButton.getId() : falseButton.getId());
//        trueButton.setClickable(false);
//        falseButton.setClickable(false);
//
//        if (correctAnswer){
//            trueButton.setBackgroundColor(getResources().getColor(R.color.colorCorrectAnswer));
//            falseButton.setBackgroundColor(getResources().getColor(R.color.colorWrongAnswer));
//        }
//        else {
//            trueButton.setBackgroundColor(getResources().getColor(R.color.colorWrongAnswer));
//            falseButton.setBackgroundColor(getResources().getColor(R.color.colorCorrectAnswer));
//        }
//
//        Log.i(TAG, "true = " + trueButton+ "\nfalse = " + falseButton);
//        Log.i(TAG, "finished handleQuestionTrueFalse");
    }


    /**
     * handleQuestionChoiceOLD handle the case that the question being displayed is a selection question
     * Bundle must hold the following:
     *     "answerList" : List<String> (a list of possible answers)
     *     "correctAnswers" : List<Integer> (a list of indices into the answerList that are correct answers)
     *     "studentAnswers" : Integer (an indices into the answerList that was the student answers)
     * @param info
     *//*
    //TODO: ChoiceOLD questions: radio buttons not working, will need to create own version OR
    //TODO:     find a way to dynamically add more radio buttons to a radio group
    private void handleQuestionChoiceOLD(Bundle info){
        mRadioGroupChoiceOLD.setVisibility(View.VISIBLE);
        mRecyclerViewChoiceOLD.setVisibility(View.VISIBLE);

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


        mRecyclerViewChoiceOLD.setHasFixedSize(false);
        recyclerLayoutManager = new LinearLayoutManager(this);
        mRecyclerViewChoiceOLD.setLayoutManager(recyclerLayoutManager);
        choiceAdapter = new AnswerChoiceOLDOLDRecyclerAdapter(getApplicationContext(), answerList, correctAnswers, studentAnswer, true);

        choiceAdapter.setItemChoiceClickListener(new ItemChoiceClickListener() {
            @Override
            public void onClick(View view, int position) {
                //do nothing
            }
        });

        mRecyclerViewChoiceOLD.setAdapter(choiceAdapter);

        Log.i(TAG, "finished handleQuestionChoiceOLD");
    }*/



}
