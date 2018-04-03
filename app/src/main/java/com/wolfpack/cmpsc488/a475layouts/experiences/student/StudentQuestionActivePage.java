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
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyStartedService;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.QuestionInformation;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;


// given
// we ask for the current question
// we submit from here
public class StudentQuestionActivePage extends QuestionPage {
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

                    Log.i(TAG, "OUR QUESTION JSON: " + questionStringJSON);

                    //make our object when we receive a question
                    questionInformation = gson.fromJson(questionStringJSON,
                            QuestionInformation.class);

                    //https://stackoverflow.com/questions/18544133/parsing-json-array-into-java-util-list-with-gson
                    Type listType = new TypeToken<List<String>>(){}.getType();
                    Type listType2 = new TypeToken<List<String>>(){}.getType();

                    potentialAnswers = gson.fromJson(questionInformation.getPotentialAnswers(), listType);

                    for(String el: potentialAnswers){
                        Log.i(TAG, "onReceive: POTENTIAL ANSWERS: "  + el);
                    }

                    correctAnswers = gson.fromJson(questionInformation.getCorrectAnswers(), listType2);

                    for(String el: correctAnswers){
                        Log.i(TAG, "onReceive: CORRECT KEY(S): "  + el);
                    }

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





    private RecyclerView.LayoutManager recyclerLayoutManager;
    private AnswerChoiceRecyclerAdapter choiceAdapter;

    private Boolean[] studentAnswersChoice = null;
    private Boolean studentAnswersTrueFalse = null;

    protected void handleActiveQuestion(QuestionInformation info){

        questionDesc = info.getDescription();
        mTextViewQuestion.setText(info.getDescription());


        Log.i("handleActiveQuestion", "teacher id = " + info.getTeacherId() + "\n" +
                "question id = " + info.getQuestionId() + "\n" +
                "question desc = " + info.getDescription() + "\n" +
                "question type = " + info.getQuestionType() + "\n" +
                "potential answers = " + info.getPotentialAnswers() + "\n" +
                "correct answers = " + info.getCorrectAnswers() + "\n");


        if (info.getQuestionType().startsWith("True")){
            handleQuestionTrueFalse(info);
        }
        else if (info.getQuestionType().startsWith("Multiple")){
            handleQuestionChoice(info);
        }

    }


    protected void handleQuestionChoice(QuestionInformation info){
        mRecyclerViewChoice.setVisibility(View.VISIBLE);

        //ArrayList<String> answerList = info.getStringArrayList("answerList");
        //ArrayList<Integer> correctAnswers = info.getIntegerArrayList("correctAnswers");
        //ArrayList<Integer> studentAnswers = info.getIntegerArrayList("studentAnswers");

        //ArrayList<String> answerList = new ArrayList<>();
        ArrayList<Integer> correctAnswers = new ArrayList<>();

//        Matcher matcher = Pattern.compile("([^\"]\\S*|\".+?\")\\s*").matcher(info.getPotentialAnswers());
//        while(matcher.find())
//            answerList.add(matcher.group(1));
//
//        matcher = Pattern.compile("([^\"]\\S*|\".+?\")\\s*").matcher(info.getPotentialAnswers());
//        while(matcher.find())
//            correctAnswers.add(Integer.parseInt(matcher.group(1)));

        String answerString = info.getPotentialAnswers();
        answerString = answerString.substring(2, answerString.length() - 2);
        ArrayList<String> answerList = new ArrayList<>(Arrays.asList(answerString.split("\",\"")));


        String correctString = info.getCorrectAnswers();
        correctString = correctString.substring(2, correctString.length() - 2);
        for (String s : correctString.split("\",\"")){
            correctAnswers.add(Integer.parseInt(s) - 1);
        }

        studentAnswersChoice = new Boolean[answerList.size()];


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
                studentAnswersChoice[position] = (studentAnswersChoice[position] == null) || !studentAnswersChoice[position];
                Toast.makeText(view.getContext(), "Choose answer (" + position + "): " + studentAnswersChoice[position], Toast.LENGTH_SHORT).show();
            }
        });

        mRecyclerViewChoice.setAdapter(choiceAdapter);

        Log.i(TAG, "finished handleQuestionChoice");
    }


    protected void handleQuestionTrueFalse(QuestionInformation info) {
        mRadioGroupTrueFalse.setVisibility(View.VISIBLE);

        RadioButton trueButton= (RadioButton) mRadioGroupTrueFalse.getChildAt(0);
        RadioButton falseButton = (RadioButton) mRadioGroupTrueFalse.getChildAt(1);

        trueButton.setClickable(true);
        falseButton.setClickable(true);

        trueButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                studentAnswersTrueFalse = true;
                //Toast.makeText(getApplicationContext(), "Choice: " + studentAnswersTrueFalse, Toast.LENGTH_SHORT).show();
            }
        });

        falseButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                studentAnswersTrueFalse = false;
                //Toast.makeText(getApplicationContext(), "Choice: " + studentAnswersTrueFalse, Toast.LENGTH_SHORT).show();
            }
        });

    }


//    public void onTrueFalseButtonClicked(View view){
//        switch(view.getId()){
//            case R.id.answerTrue:
//                studentAnswersTrueFalse = true;
//                break;
//            case R.id.answerFalse:
//                studentAnswersTrueFalse = false;
//                break;
//            default:
//                throw new RuntimeException("True or False not picked somehow");
//        }
//    }



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

                    String newQuestionId =
                            info.getString(MyStartedService.MY_SERVICE_QUESTION_ID);
                    String newQuestionSessionId =
                            info.getString(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID);
                    String newQuestionHistoryId =
                            info.getString(MyStartedService.MY_SERVICE_QUESTION_HISTORY_ID);
                    String newQuestionSetId =
                            info.getString(MyStartedService.MY_SERVICE_QUESTION_SET_ID);

                    //we have a new question
                    if(!questionInformation.getQuestionId().equals(newQuestionId) ||
                            !questionHistoryId.equals(newQuestionHistoryId)
                            ){

                        //we have a new question!
                        Toast.makeText(StudentQuestionActivePage.this,
                                "NEW QUESTION",
                                Toast.LENGTH_SHORT).show();

                        //TODO: quesiton has ended, BUT new question is already available
                        submitFinalAnswer();

                    }

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
                        "LE NEW Q OR SESSION",
                        Toast.LENGTH_SHORT).show();

                //TODO: show graphic display of dead values?
                onQuestionComplete();

                submitFinalAnswer();

            }


        }
    };



    public void onQuestionComplete(){

        if(answerType.startsWith("Multiple")){
            choiceAdapter.onQuestionCompleted();
        }
        else if (answerType.startsWith("True")){
            boolean correctAnswer = Boolean.parseBoolean(questionInformation.getCorrectAnswers());
            RadioButton trueButton= (RadioButton) mRadioGroupTrueFalse.getChildAt(0);
            RadioButton falseButton = (RadioButton) mRadioGroupTrueFalse.getChildAt(1);

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

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(validateQuestionReceiver,
                        new IntentFilter(MyStartedService.MY_SERVICE_VALIDATE_ANSWER));

        LocalBroadcastManager.getInstance(
                getApplicationContext())
                .registerReceiver(submitAnswerReceiver, new IntentFilter(
                        MyStartedService.MY_SERVICE_SUBMIT_ANSWER));

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
    }

    private void submitFinalAnswer(){
        Log.i(TAG, "submitFinalAnswer");
        //TODO: TEST FINAL ANSWER SUBMISSION
        submittedFinalAnswer = true;
        //TODO: create JSON string from user answers
        if(answer == null || answer.equals(""))
        {
            answer = "[\"0\"]"; //no answer was provided
        }

        mService.submitAnswer(
                studentId,
                questionSessionId,
                questionHistoryId,
                answerType,
                answer,
                "true"
        );

    }


    private void submitPeriodicAnswer(){
        Log.i(TAG, "submitPeriodicAnswer");

        //TODO: TEST PERIODIC ANSWER SUBMISSION

        //TODO: create JSON string from user answers
        if(answer == null || answer.equals(""))
            answer = "[]"; //no answer was provided

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