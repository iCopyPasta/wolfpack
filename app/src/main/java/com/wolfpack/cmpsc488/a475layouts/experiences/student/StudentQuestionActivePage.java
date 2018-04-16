package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.annotation.SuppressLint;
import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.ContentValues;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.content.SharedPreferences;
import android.database.sqlite.SQLiteConstraintException;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.IBinder;
import android.support.v4.content.LocalBroadcastManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.widget.RadioButton;
import android.widget.Toast;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyStartedService;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.ActiveCombinationResults;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.QuestionInformation;

import java.lang.reflect.Type;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.List;
import java.util.Locale;


// given
// we ask for the current question
// we submit from here
public class StudentQuestionActivePage extends QuestionPage {

    public static final String TAG = "QuestionActivePage";

    //private String studentId = null;
    //private String classId = null;
    //private String className = null;

    private String questionSetId = null;
    //private String questionId = null;
    private String questionSessionId = null;
    private String questionHistoryId = null;
    private String questionStringJSON = null;
    //private QuestionInformation questionInformation = null;
    private String answerType = null;
    private String answer = null;
    private boolean[] studentAnswers = null;
    private Gson gson = null;

    private int errorCount = 0;
    private boolean submittedFinalAnswer = false;

    private MyStartedService mService;

    private final ServiceConnection mServiceConn = new ServiceConnection() {
        @Override
        public void onServiceConnected(ComponentName componentName, IBinder iBinder) {
            MyStartedService.MyServiceBinder myServiceBinder =
                    (MyStartedService.MyServiceBinder) iBinder;

            mService = myServiceBinder.getService();

            if(mService == null){
                Log.e(TAG, "mService is null");
            } else{

                if(questionId != null){
                    Log.i(TAG, "onServiceConnected: myService and questionId are not null");
                    mService.searchLiveQuestionInfo(questionId, "true");
                }
            }
        }

        @Override
        public void onServiceDisconnected(ComponentName componentName) {
            if(mService != null){
                Log.i(TAG, "onServiceDisconnected: " + TAG + " disconnected from MyStartedService");
            }
        }
    };

    //received live question information
    private BroadcastReceiver questionInfoReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();


            if(info != null){
                Log.i(TAG, "onReceive: " + "mReceiver -> message received");

                // an active question! set up our information on the activity
                // only need to set values once, in case of multiple requests due to timing
                if(questionInformation == null){
                    questionStringJSON = info.getString(
                            getString(R.string.KEY_MY_SERVICE_QUESTION_INFO_JSON),""
                    );

                    //Log.i(TAG, "OUR QUESTION JSON: " + questionStringJSON);

                    //make our object when we receive a question
                    questionInformation = gson.fromJson(questionStringJSON,
                            QuestionInformation.class);

                    //https://stackoverflow.com/questions/18544133/parsing-json-array-into-java-util-list-with-gson
//                    Type listType = new TypeToken<List<String>>(){}.getType();
//                    Type listType2 = new TypeToken<List<String>>(){}.getType();
//
//                    potentialAnswerList = gson.fromJson(questionInformation.getPotentialAnswers(), listType);
//
//                    for(String el: potentialAnswerList){
//                        Log.i(TAG, "onReceive: POTENTIAL ANSWERS: "  + el);
//                    }
//
//                    correctAnswerList = gson.fromJson(questionInformation.getCorrectAnswers(), listType2);
//
//                    for(Integer el: correctAnswerList){
//                        Log.i(TAG, "onReceive: CORRECT KEY(S): "  + el);
//                    }

                    answerType = questionInformation.getQuestionType();


                    Log.i(TAG, "onReceive: " + questionInformation.getDescription());
                    Toast.makeText(StudentQuestionActivePage.this,
                            questionInformation.getQuestionType(),
                            Toast.LENGTH_SHORT).show();

                    mService.validateSameQuestion(questionSetId,
                            questionId,
                            questionSessionId,
                            questionHistoryId,
                            "true");


                    handleActiveQuestion(questionInformation);
                }
            }
            else{
                errorCount++;
                mService.searchLiveQuestionInfo(questionSetId, "false");

                if(errorCount > 3){
                    Log.e(TAG, "onReceive: " + "error in retrieving question info " + classId);
                    Toast.makeText(StudentQuestionActivePage.this,
                            "Error", Toast.LENGTH_SHORT).show();

                    finish();
                }
            }

        }
    };

    private BroadcastReceiver combinationQuery = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();
            Log.i(TAG, "onReceive: combinationQuery" );

            if(info != null){
                String newQuestionId =
                        info.getString(getString(R.string.KEY_MY_SERVICE_QUESTION_ID), "");
                String newQuestionSessionId =
                        info.getString(getString(R.string.KEY_MY_SERVICE_QUESTION_SESSION_ID), "");
                String newQuestionHistoryId =
                        info.getString(getString(R.string.KEY_MY_SERVICE_QUESTION_HISTORY_ID), "");
                String newQuestionSetId =
                        info.getString(getString(R.string.KEY_MY_SERVICE_QUESTION_SET_ID), "");

                Log.i(TAG, "onReceive: questionId: " + newQuestionId);
                Log.i(TAG, "onReceive: newQuestionSessionId " + newQuestionSessionId);
                Log.i(TAG, "onReceive: newQuestionHistoryId " + newQuestionHistoryId);
                Log.i(TAG, "onReceive: newQuestionSetId " + newQuestionSetId);

                if(!newQuestionSessionId.equals(questionSessionId)){
                    //TODO: put 'your session has expired' dialog alert
                    Toast.makeText(getApplicationContext(),"New Session",
                            Toast.LENGTH_SHORT).show();
                }

                else if(!newQuestionId.equals("") &&
                        !newQuestionId.equals(questionId)){
                    //TODO: put 'new question, Join?' dialog alert
                    Toast.makeText(getApplicationContext(),"New Question",
                            Toast.LENGTH_SHORT).show();
                }
                else
                    mService.searchActiveSandQ(classId, questionSetId, "false");
            }

        }
    };


    protected void handleActiveQuestion(QuestionInformation info){
        addQuestion(info, sessionId);

        questionDesc = info.getDescription();
        mTextViewQuestion.setText(info.getDescription());

        Log.i("handleActiveQuestion", "teacher id = " + info.getTeacherId() + "\n" +
                "question id = " + info.getQuestionId() + "\n" +
                "question desc = " + info.getDescription() + "\n" +
                "question type = " + info.getQuestionType() + "\n" +
                "potential answers = " + info.getPotentialAnswers() + "\n" +
                "correct answers = " + info.getCorrectAnswers() + "\n\n");


        if (info.getQuestionType().equals(getString(R.string.QUESTION_TYPE_TRUE_FALSE))){
            handleQuestionTrueFalse(info);
        }
        else if (info.getQuestionType().equals(getString(R.string.QUESTION_TYPE_CHOICE))){
            handleQuestionChoice(info);
        }

    }


    protected void handleQuestionChoice(QuestionInformation info){
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

        studentAnswers = new boolean[potentialAnswerList.size()];


        Log.i("handleActiveQuestion", "teacher id = " + info.getTeacherId() + "\n" +
                "question id = " + info.getQuestionId() + "\n" +
                "question desc = " + info.getDescription() + "\n" +
                "question type = " + info.getQuestionType() + "\n" +
                "potential answers = " + potentialAnswerList + "\n" +
                "correct answers = " + correctAnswerList + "\n\n");


        mRecyclerViewChoice.setHasFixedSize(false);
        recyclerLayoutManager = new LinearLayoutManager(this);
        mRecyclerViewChoice.setLayoutManager(recyclerLayoutManager);
        choiceAdapter = new AnswerChoiceRecyclerAdapter(getApplicationContext(),
                potentialAnswerList,
                correctAnswerList,
                true);


        choiceAdapter.setItemChoiceClickListener(new ItemChoiceClickListener() {
            @Override
            public void onClick(View view, int position) {
                studentAnswers[position] = !studentAnswers[position];
                Toast.makeText(view.getContext(), "Choose answer (" + position + "): " + studentAnswers[position], Toast.LENGTH_SHORT).show();
            }
        });

        mRecyclerViewChoice.setAdapter(choiceAdapter);

        Log.i(TAG, "finished handleQuestionChoice");
    }


    protected void handleQuestionTrueFalse(QuestionInformation info) {
        studentAnswers = new boolean[]{false, false};

        mRadioGroupTrueFalse.setVisibility(View.VISIBLE);

        RadioButton trueButton= (RadioButton) mRadioGroupTrueFalse.getChildAt(0);
        RadioButton falseButton = (RadioButton) mRadioGroupTrueFalse.getChildAt(1);

        trueButton.setClickable(true);
        falseButton.setClickable(true);

        trueButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                studentAnswers[0] = true;
                studentAnswers[1] = false;
                //Toast.makeText(getApplicationContext(), "Choice: " + studentAnswersTrueFalse, Toast.LENGTH_SHORT).show();
            }
        });

        falseButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                studentAnswers[0] = false;
                studentAnswers[1] = true;
                //Toast.makeText(getApplicationContext(), "Choice: " + studentAnswersTrueFalse, Toast.LENGTH_SHORT).show();
            }
        });

    }


    @SuppressLint("StaticFieldLeak")
    private void addQuestion(final QuestionInformation info, final String sessionId){

        new AsyncTask<Void, Void, Boolean> (){

            @Override
            protected Boolean doInBackground(Void... params) {

                questionId = info.getQuestionId();
                questionDesc = info.getDescription();
                questionType = info.getQuestionType();
                questionPotentialAnswers = info.getPotentialAnswers();
                questionCorrectAnswers = info.getCorrectAnswers();
                questionStudentAnswers = "";

                int question_id = Integer.parseInt(questionId);
                int session_id = Integer.parseInt(sessionId);

                String table = getString(R.string.TABLE_QUESTION);

                ContentValues values = new ContentValues();
                values.put("_id", question_id);
                values.put("question_type", questionType);
                values.put("description", questionDesc);
                values.put("potential_answers", questionPotentialAnswers);
                values.put("correct_answers", questionCorrectAnswers);
                values.put("student_answers", questionStudentAnswers);

                long questionInsertResult = 0;

                try {
                    questionInsertResult = db.insert(table, null, values);
                }
                catch (SQLiteConstraintException e){
                    Log.i(TAG, "row already exists: questionInsertResult = " + questionInsertResult);
                    Log.d(TAG, "message: " + e.getMessage());
                    questionInsertResult = 1;
                }

                table = getString(R.string.TABLE_Q_IS_IN);

                values = new ContentValues();
                values.put("session_id", session_id);
                values.put("question_id", question_id);

                long qIsInInsertResult = 0;

                try {
                    qIsInInsertResult = db.insert(table, null, values);
                }
                catch (SQLiteConstraintException e){
                    Log.i(TAG, "row already exists: qIsInInsertResult = " + qIsInInsertResult);
                    Log.d(TAG, "message: " + e.getMessage());
                    qIsInInsertResult = 1;
                }

                return questionInsertResult == 1 && qIsInInsertResult == 1;
            }

            @Override
            protected void onPostExecute(Boolean result){
                if (result) {
                    Log.i(TAG, "success: rows inserted");
                    Toast.makeText(StudentQuestionActivePage.this,
                            "question was inserted successfully!",
                            Toast.LENGTH_SHORT).show();
                }
            }


        }.execute();

    }




    //receiver for validating the active question
    private BroadcastReceiver validateQuestionReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();

            //only get structure if our status is the exact same
            if(info != null){
                Log.i(TAG, "onReceive: " + "activeQuestionReceiver -> message received");


                if(questionInformation != null){

                    Log.i(TAG, "onReceive: android sees same exact question ");

                    submitPeriodicAnswer();
                    Log.i(TAG, "onReceive: ");
                }
                //you may not have gotten an answer for your lifetime info, yet, try up to 3 times
                else{
                    mService.searchLiveQuestionInfo(questionId,"false");
                }

            }
            else{
                Log.i(TAG, "onReceive: " + "no active questionId for " +
                        "questionSessionId = " + questionSessionId + ", " +
                        "quesitonSetId = " + questionSetId);
                Log.i(TAG, "onReceive: " + "sending BACK to StudentSessionPage");

                // something didn't match and we have no results, implying new question

                //we have a new question!
                Toast.makeText(StudentQuestionActivePage.this,
                        "Question Over",
                        Toast.LENGTH_SHORT).show();

                //TODO: show graphic display of dead values?
                //Log.d("WHAT THE FUCK!!!!", "starting onQuestionComplete()");
                onQuestionComplete();

                submitFinalAnswer();

            }


        }
    };



    public void onQuestionComplete(){
      
        Log.i(TAG, "onQuestionComplete: ON QUESTION COMPLETED");


        if(answerType.equals(getString(R.string.QUESTION_TYPE_CHOICE))){
            //Log.d("WHAT THE FUCK!!!!", "question is a " + getString(R.string.QUESTION_TYPE_CHOICE));
            choiceAdapter.onQuestionCompleted();
        }
        else if (answerType.equals(getString(R.string.QUESTION_TYPE_TRUE_FALSE))){
            //Log.d("WHAT THE FUCK!!!!", "question is a " + getString(R.string.QUESTION_TYPE_TRUE_FALSE));
            //boolean correctAnswer = Boolean.parseBoolean(questionInformation.getCorrectAnswers());\
            boolean correctAnswer = questionInformation.getCorrectAnswers().equals("[\"1\"]");
            Log.i(TAG,"correct answer: " + questionInformation.getCorrectAnswers() + "\n" +
                    "boolean is : " + correctAnswer);
          
            RadioButton trueButton= (RadioButton) mRadioGroupTrueFalse.getChildAt(0);
            RadioButton falseButton = (RadioButton) mRadioGroupTrueFalse.getChildAt(1);

            trueButton.setClickable(false);
            falseButton.setClickable(false);

            trueButton.setTextColor(
                    (correctAnswer) ? getResources().getColor(R.color.colorCorrectAnswer)
                            : getResources().getColor(R.color.colorWrongAnswer));

            falseButton.setTextColor(
                    (!correctAnswer) ? getResources().getColor(R.color.colorCorrectAnswer)
                            : getResources().getColor(R.color.colorWrongAnswer));


        }

    }

    private BroadcastReceiver submitAnswerReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();
            Log.i(TAG, "onReceive: " + "service message received");

            if(info != null){
                //did our answer get successfully submitted?
                Log.i(TAG, "onReceive: " + "our question was uploaded");

                if(!submittedFinalAnswer)
                    mService.validateSameQuestion(questionSetId,
                            questionId,
                            questionSessionId,
                            questionHistoryId,
                            "true");

            }
            else{
                Log.i(TAG, "onReceive: " + "our question was not uploaded");
            }

            if(submittedFinalAnswer){
                mService.searchActiveSandQ(classId, questionSetId, "false");
            }
        }
    };


    @Override
    public void onCreate(Bundle savedInstanceState){
        super.onCreate(savedInstanceState);

        Bundle bundle = getIntent().getExtras();

        if(bundle != null){
            questionId = bundle.getString(getString(R.string.KEY_MY_SERVICE_QUESTION_ID), "");
            questionSessionId = bundle.getString(getString(R.string.KEY_MY_SERVICE_QUESTION_SESSION_ID), "");
            questionHistoryId = bundle.getString(getString(R.string.KEY_MY_SERVICE_QUESTION_HISTORY_ID), "");
            questionSetId = bundle.getString(getString(R.string.KEY_MY_SERVICE_QUESTION_SET_ID), "");

            SharedPreferences sharedPref = getSharedPreferences(
                    getString(R.string.preference_file_key), Context.MODE_PRIVATE);

            studentId = sharedPref.getString(getString(R.string.STUDENT_ID),"");

            classId = bundle.getString("classId");
            className = bundle.getString("className");

            sessionId = questionSessionId;




        }

        gson = new Gson();
    }

    @Override
    public void onStart(){
        super.onStart();
    }

    @Override
    public void onResume(){
        super.onResume();

        //bind to custom service
        Intent serviceIntent = new Intent(StudentQuestionActivePage.this ,
                MyStartedService.class);
        startService(serviceIntent);
        bindService(serviceIntent, mServiceConn, Context.BIND_AUTO_CREATE);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(questionInfoReceiver, new IntentFilter(
                        getString(R.string.KEY_MY_SERVICE_QUESTION_INFO)));

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(validateQuestionReceiver,
                        new IntentFilter(getString(R.string.KEY_MY_SERVICE_VALIDATE_ANSWER)));

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(submitAnswerReceiver, new IntentFilter(
                        getString(R.string.KEY_MY_SERVICE_SUBMIT_ANSWER)));

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(combinationQuery, new IntentFilter(
                        getString(R.string.KEY_MY_SERVICE_VALIDATE_COMBO)));

    }

    @Override
    public void onStop(){
        super.onStop();

        unbindService(mServiceConn);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .unregisterReceiver(submitAnswerReceiver);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .unregisterReceiver(validateQuestionReceiver);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .unregisterReceiver(questionInfoReceiver);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .unregisterReceiver(combinationQuery);
    }

    private synchronized void submitFinalAnswer(){
        Log.i(TAG, "submitFinalAnswer");

        submittedFinalAnswer = true;
        int count = 0;

        if(answer == null || answer.equals(""))
        {
            for(boolean el: studentAnswers){
                if(el)
                    count++;
            }

            StringBuilder sb = new StringBuilder();
            sb.append("[");
            Log.i(TAG, "submitPeriodicAnswer: LENGTH OF STUDENT ANSWERS " + studentAnswers.length);
            for (int i = 0; i < studentAnswers.length; i++){
                if(studentAnswers[i]){
                    sb.append("\"");
                    sb.append(i);
                    sb.append("\"");
                    if (count > 1){
                        sb.append(",");
                        count--;
                    }
                }
            }
            sb.append("]");
            answer = sb.toString();
        }


        mService.submitAnswer(
                studentId,
                questionSessionId,
                questionHistoryId,
                answerType,
                answer,
                "true"
        );
        Log.i(TAG, "submitFinalAnswer: SUBMITTING ANSWER: " + answer);

        updateQuestion(answer);

        answer = null;
    }


    private synchronized void submitPeriodicAnswer(){
        Log.i(TAG, "submitPeriodicAnswer");

        int count = 0;
        if(answer == null || answer.equals(""))
        {
            for(boolean el: studentAnswers){
                if(el)
                    count++;
            }

            StringBuilder sb = new StringBuilder();
            sb.append("[");
            Log.i(TAG, "submitPeriodicAnswer: LENGTH OF STUDENT ANSWERS " + studentAnswers.length);
            for (int i = 0; i < studentAnswers.length; i++){
                if(studentAnswers[i]){
                    sb.append("\"");
                    sb.append(i);
                    sb.append("\"");
                    if (count > 1){
                        sb.append(",");
                        count--;
                    }
                }
            }
            sb.append("]");
            answer = sb.toString();
        }


        mService.submitAnswer(
                studentId,
                questionSessionId,
                questionHistoryId,
                answerType,
                answer,
                "false"
        );
        Log.i(TAG, "submitFinalAnswer: SUBMITTING ANSWER: " + answer);

        answer = null;

    }




    @SuppressLint("StaticFieldLeak")
    private void updateQuestion(final String studentAnswer){

        new AsyncTask<Void, Void, Void>(){

            @Override
            protected Void doInBackground(Void... voids) {

                String table = getString(R.string.TABLE_QUESTION);
                ContentValues values = new ContentValues();
                values.put("student_answers", studentAnswer);

                String selection = "_id = ?";
                String[] selectionArgs = { String.valueOf(questionId) };

                db.update(table, values, selection, selectionArgs);

                return null;
            }

        }.execute();






    }







}