package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.content.SharedPreferences;
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
import com.wolfpack.cmpsc488.a475layouts.services.authentication.LoginDetails;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyStartedService;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.ActiveCombinationResults;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.QuestionInformation;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;


// given
// we ask for the current question
// we submit from here
public class StudentQuestionActivePage extends QuestionPage
implements AlertLeaveNewSessionDialog.AlertLeaveNewSessionDialogListener,
        AlertNewQuestionDialog.AlertNewQuestionDialogListener{
    private String studentId = null;
    private String questionId = null;
    private String questionSessionId = null;
    private String questionHistoryId = null;
    private String questionStringJSON = null;
    private ArrayList<String> potentialAnswers = null;
    private ArrayList<String> correctAnswers = null;
    private QuestionInformation questionInformation = null;
    private String answerType = null;
    private String answer = null;
    private final String TAG = "QuestionActivePage";
    private Gson gson = null;
    private String classId = null;
    //private String className = null;
    private String questionSetId = null;
    private int errorCount = 0;
    private boolean submittedFinalAnswer = false;
    private boolean isShowing = false;
    private boolean questionOver = false;
    String newQuestionId;
    String newQuestionSessionId;
    String newQuestionHistoryId;
    String newQuestionSetId;

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
                            MyStartedService.MY_SERVICE_QUESTION_INFO_JSON,""
                    );

                    //Log.i(TAG, "OUR QUESTION JSON: " + questionStringJSON);

                    //make our object when we receive a question
                    questionInformation = gson.fromJson(questionStringJSON,
                            QuestionInformation.class);

                    //https://stackoverflow.com/questions/18544133/parsing-json-array-into-java-util-list-with-gson
                    Type listType = new TypeToken<List<String>>(){}.getType();
                    Type listType2 = new TypeToken<List<String>>(){}.getType();

                    potentialAnswers = gson.fromJson(questionInformation.getPotentialAnswers(), listType);

                    /*for(String el: potentialAnswers){
                        Log.i(TAG, "onReceive: POTENTIAL ANSWERS: "  + el);
                    }*/

                    correctAnswers = gson.fromJson(questionInformation.getCorrectAnswers(), listType2);

                    /*for(String el: correctAnswers){
                        Log.i(TAG, "onReceive: CORRECT KEY(S): "  + el);
                    }*/

                    answerType = questionInformation.getQuestionType();


                    //Log.i(TAG, "onReceive: " + questionInformation.getDescription());

                    mService.searchActiveSandQ(classId, questionSetId, "false");

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
            //Log.i(TAG, "onReceive: combinationQuery" );

            if(info != null){
                newQuestionId =
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_ID, "");
                newQuestionSessionId =
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID, "");
                newQuestionHistoryId =
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_HISTORY_ID, "");
                newQuestionSetId =
                        info.getString(MyStartedService.MY_SERVICE_QUESTION_SET_ID, "");

                Log.i(TAG, "onReceive: QuestionId: " + questionId + " versus " + newQuestionId);
                Log.i(TAG, "onReceive: QuestionHistoryId: " + questionHistoryId + " versus " + newQuestionHistoryId);
                Log.i(TAG, "onReceive: QuestionSessionId: " + questionSessionId + " versus " + newQuestionSessionId);
                Log.i(TAG, "onReceive: QuestionSetId: " + questionSetId + " versus " + newQuestionSetId);

                if(!questionOver){
                    //same question as the first time on this activity
                    if(questionHistoryId.equals(newQuestionHistoryId) &&
                            questionId.equals(newQuestionId) &&
                            questionSessionId.equals(newQuestionSessionId) &&
                            questionSetId.equals(newQuestionSetId)){
                        Log.i(TAG, "onReceive: same question as when we first entered");
                        submitPeriodicAnswer();

                    }
                    //question is over
                    else if(newQuestionId.equals("") || newQuestionHistoryId.equals("")){
                        Toast.makeText(getApplicationContext(), "Question Over", Toast.LENGTH_SHORT).show();
                        Log.i(TAG, "onReceive: original question over");
                        questionOver = true;
                        terminateQuestion();
                    }
                    else if(newQuestionSessionId.equals("")){
                        Log.i(TAG, "question isn't over but the session died?");
                        questionOver = true;
                        if(!isShowing){
                            isShowing = true;
                            new AlertLeaveNewSessionDialog().show(getFragmentManager(), "Exit Session");
                        }
                    } else if((!newQuestionId.equals("") &&
                            !newQuestionId.equals(questionId)) || (!newQuestionHistoryId.equals("") &&
                            !newQuestionHistoryId.equals(questionHistoryId))){
                        Log.i(TAG, "onReceive: NEW QUESTION but was in !questionOver ");
                            terminateQuestion();
                    }
                }else{
                    Log.i(TAG, "ORIGINAL QUESTION OVER ELSE");

                    if(!newQuestionSessionId.equals("") && !newQuestionSessionId.equals(questionSessionId)){
                        if(!isShowing){
                            Log.i(TAG, "onReceive: display EXIT SESSION dialog");
                            isShowing = true;
                            new AlertLeaveNewSessionDialog().show(getFragmentManager(), "Exit Session");
                        }


                    } else if((!newQuestionId.equals("") &&
                            !newQuestionId.equals(questionId)) || (!newQuestionHistoryId.equals("") &&
                            !newQuestionHistoryId.equals(questionHistoryId))){
                            Log.i(TAG, "onReceive: display NEW QUESTION dialog");

                        if(!isShowing){
                            isShowing = true;
                            new AlertNewQuestionDialog().show(getFragmentManager(), "New Question");
                        }
                    } else if(newQuestionSessionId.equals("") && newQuestionHistoryId.equals("") &&
                            newQuestionId.equals("") && newQuestionSetId.equals("")){
                            if(!isShowing){
                                Log.i(TAG, "onReceive: ALL VALUES WERE EMPTY FROM RESPONSE ");
                                isShowing = true;
                                new AlertLeaveNewSessionDialog().show(getFragmentManager(), "Exit Session");
                            }

                    }
                    else{
                        Log.i(TAG, "onReceive: CONTINUING TO ASK SERVER QUESTION");
                        mService.searchActiveSandQ(classId, questionSetId, "false");
                    }

                }

            }

        }
    };
    
    private RecyclerView.LayoutManager recyclerLayoutManager;
    private AnswerChoiceRecyclerAdapter choiceAdapter;

    private boolean[] studentAnswers = null;

    protected void handleActiveQuestion(QuestionInformation info){

        questionDesc = info.getDescription();
        mTextViewQuestion.setText(info.getDescription());


        Log.i("handleActiveQuestion", "teacher id = " + info.getTeacherId() + "\n" +
                "question id = " + info.getQuestionId() + "\n" +
                "question desc = " + info.getDescription() + "\n" +
                "question type = " + info.getQuestionType() + "\n" +
                "potential answers = " + info.getPotentialAnswers() + "\n" +
                "correct answers = " + info.getCorrectAnswers() + "\n");


        if (info.getQuestionType().equals(getString(R.string.QUESTION_TYPE_TRUE_FALSE))){
            handleQuestionTrueFalse(info);
        }
        else if (info.getQuestionType().equals(getString(R.string.QUESTION_TYPE_CHOICE))){
            handleQuestionChoice(info);
        }

    }


    protected void handleQuestionChoice(QuestionInformation info){
        mRecyclerViewChoice.setVisibility(View.VISIBLE);

        ArrayList<Integer> correctAnswers = new ArrayList<>();


        String answerString = info.getPotentialAnswers();
        answerString = answerString.substring(2, answerString.length() - 2);
        ArrayList<String> answerList = new ArrayList<>(Arrays.asList(answerString.split("\",\"")));


        String correctString = info.getCorrectAnswers();
        correctString = correctString.substring(2, correctString.length() - 2);
        for (String s : correctString.split("\",\"")){
            correctAnswers.add(Integer.parseInt(s) - 1);
        }

        studentAnswers = new boolean[answerList.size()];


        Log.i("handleActiveQuestion", "teacher id = " + info.getTeacherId() + "\n" +
                "question id = " + info.getQuestionId() + "\n" +
                "question desc = " + info.getDescription() + "\n" +
                "question type = " + info.getQuestionType() + "\n" +
                "potential answers = " + answerList + "\n" +
                "correct answers = " + correctAnswers
                + "\n");


        mRecyclerViewChoice.setHasFixedSize(false);
        recyclerLayoutManager = new LinearLayoutManager(this);
        mRecyclerViewChoice.setLayoutManager(recyclerLayoutManager);
        choiceAdapter = new AnswerChoiceRecyclerAdapter(getApplicationContext(),
                answerList,
                correctAnswers,
                true);


        choiceAdapter.setItemChoiceClickListener(new ItemChoiceClickListener() {
            @Override
            public void onClick(View view, int position) {
                studentAnswers[position] = !studentAnswers[position];
                //Toast.makeText(view.getContext(), "Choose answer (" + position + "): " + studentAnswers[position], Toast.LENGTH_SHORT).show();
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


    //receiver for validating the active question
    /*private BroadcastReceiver validateQuestionReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();

            //only get structure if our status is the exact same
            if(info != null){
                Log.i(TAG, "onReceive: " + "activeQuestionReceiver -> message received");

                if(questionInformation != null){

                    Log.i(TAG, "onReceive: android sees same exact question ");

                    submitPeriodicAnswer();
                    Log.i(TAG, "onReceive: submitPeriodicAnswers called from validateQuestionReceiver");
                }
                //you may not have gotten an answer for your lifetime info, yet, try up to 3 times
                else{
                    mService.searchLiveQuestionInfo(questionId,"false");
                }

            }
            else{

            }


        }
    };*/



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

            if (correctAnswer){
                trueButton.setTextColor(getResources().getColor(R.color.colorCorrectAnswer));
                falseButton.setTextColor(getResources().getColor(R.color.colorWrongAnswer));
            }
            else {
                trueButton.setTextColor(getResources().getColor(R.color.colorWrongAnswer));
                falseButton.setTextColor(getResources().getColor(R.color.colorCorrectAnswer));
            }


        }

    }

    private BroadcastReceiver submitAnswerReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {
            Bundle info = intent.getExtras();

            if(info != null){
                //did our answer get successfully submitted?
                Log.i(TAG, "onReceive: " + "our question was uploaded");
                    mService.searchActiveSandQ(classId, questionSetId, "false");

            }
            else{
                Log.i(TAG, "onReceive: " + "our question was not uploaded");
            }

        }
    };


    @Override
    public void onCreate(Bundle savedInstanceState){
        super.onCreate(savedInstanceState);

        Intent caller = getIntent();
        Bundle callerInfo = caller.getExtras();

        if(callerInfo != null){
            questionId = callerInfo.getString(MyStartedService.MY_SERVICE_QUESTION_ID,
                    "");

            questionSessionId = callerInfo.getString(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID,
                    "");
            questionHistoryId = callerInfo.getString(MyStartedService.MY_SERVICE_QUESTION_HISTORY_ID,
                    "");

            questionSetId = callerInfo.getString(MyStartedService.MY_SERVICE_QUESTION_SET_ID,
                    "");

            SharedPreferences sharedPref = getSharedPreferences(
                    getString(R.string.preference_file_key), Context.MODE_PRIVATE);

            studentId = sharedPref.getString(getString(R.string.STUDENT_ID),"");

            classId = callerInfo.getString("classId");
            className = callerInfo.getString("className");

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
                        MyStartedService.MY_SERVICE_QUESTION_INFO));

        /*LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(validateQuestionReceiver,
                        new IntentFilter(MyStartedService.MY_SERVICE_VALIDATE_ANSWER));*/

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(submitAnswerReceiver, new IntentFilter(
                        MyStartedService.MY_SERVICE_SUBMIT_ANSWER));

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(combinationQuery, new IntentFilter(
                        MyStartedService.MY_SERVICE_VALIDATE_COMBO));

    }

    @Override
    public void onStop(){
        super.onStop();

        unbindService(mServiceConn);

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .unregisterReceiver(submitAnswerReceiver);

        /*LocalBroadcastManager.getInstance(
                getApplicationContext())
                .unregisterReceiver(validateQuestionReceiver);*/

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
            //Log.i(TAG, "submitFinalAnswer: LENGTH OF STUDENT ANSWERS " + studentAnswers.length);
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

            mService.submitAnswer(
                    studentId,
                    questionSessionId,
                    questionHistoryId,
                    answerType,
                    answer,
                    "true"
            );
            Log.i(TAG, "submitFinalAnswer: SUBMITTING ANSWER: " + answer);

            answer = null;
        }


    }

    private void submitPeriodicAnswer(){
        //Log.i(TAG, "submitPeriodicAnswer");

        int count = 0;
        if(answer == null || answer.equals(""))
        {
            for(boolean el: studentAnswers){
                if(el)
                    count++;
            }

            StringBuilder sb = new StringBuilder();
            sb.append("[");
            //Log.i(TAG, "submitPeriodicAnswer: LENGTH OF STUDENT ANSWERS " + studentAnswers.length);
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

            Log.i(TAG, "submitFinalAnswer: SUBMITTING ANSWER: " + answer);
            mService.submitAnswer(
                    studentId,
                    questionSessionId,
                    questionHistoryId,
                    answerType,
                    answer,
                    "false"
            );

            answer = null;
        }
    }

    private void terminateQuestion(){
        questionOver = true;
        if(!submittedFinalAnswer){
            Log.i(TAG, "we haven't submitted our final answer, yet in !questionOver!");
            submitFinalAnswer();
            onQuestionComplete();
        } else{

            mService.searchActiveSandQ(classId, questionSetId, "false");
        }
    }

    @Override
    public void onLeavePositiveClick() {
        finish();
    }

    @Override
    public void onLeaveNegativeClick() {

    }

    @Override
    public void onNewQPositiveClick() {

        Intent newQuestion = new Intent(StudentQuestionActivePage.this, StudentQuestionActivePage.class);
        newQuestion.putExtra("className", className);
        newQuestion.putExtra("classId", classId);
        newQuestion.putExtra(MyStartedService.MY_SERVICE_QUESTION_SET_ID, newQuestionSetId);
        newQuestion.putExtra(MyStartedService.MY_SERVICE_QUESTION_HISTORY_ID, newQuestionHistoryId);
        newQuestion.putExtra(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID, newQuestionSessionId);
        newQuestion.putExtra(MyStartedService.MY_SERVICE_QUESTION_ID, newQuestionId);

        finish();
        startActivity(newQuestion);

    }

    @Override
    public void onNewQNegativeClick() {

    }
}