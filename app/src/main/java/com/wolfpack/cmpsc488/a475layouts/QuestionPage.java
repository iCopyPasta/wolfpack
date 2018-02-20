package com.wolfpack.cmpsc488.a475layouts;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import java.util.Locale;
import java.util.concurrent.TimeUnit;

public class QuestionPage extends AppCompatActivity {
    public static final String TAG = "QuestionPage";

    private long defaultStartTimeMillis = 600000;

    private TextView mTextViewQuestion;
    private ListView mListViewAnswers;
    private TextView mTextViewCountdown;

    private boolean isTimerRunning;

    private long mTimeLeftMillis = defaultStartTimeMillis;

    private String defaultQuestion = "Rick Astley's never gonna:";
    private String[] defaultAnswers = {"Give you up", "Let you down", "Make you cry", "Hurt you"};

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_question_page);

        Log.d(TAG, "onCreate beginning");

        // TODO: get question/answers/timer from server
        mTextViewQuestion = findViewById(R.id.textView5);
        mListViewAnswers = findViewById(R.id.listView);
        mTextViewCountdown = findViewById(R.id.textView12);

        mTextViewQuestion.setText(defaultQuestion);

        ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(
                getApplicationContext(),
                android.R.layout.simple_list_item_1,
                defaultAnswers);

        mListViewAnswers.setAdapter(mAdapter);

        mListViewAnswers.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
                Toast.makeText(
                        getApplicationContext(),
                        "You Choose: " + defaultAnswers[position],
                        Toast.LENGTH_SHORT).show();
            }
        });

        //Log.d(TAG, "onCreate begin loop");


        // TODO: fix timer

        /*
        isTimerRunning = true;

        while(isTimerRunning) {
            updateCountdownText();
            mTimeLeftMillis -= 1000;
            try {
                TimeUnit.SECONDS.sleep(1);
            }
            catch (Exception e){
                Log.d(TAG, e.getMessage());
            }
            if (mTimeLeftMillis <= 0){
                isTimerRunning = false;
            }
        }
        */
    }




    private void updateCountdownText(){
        int minutes = (int) (mTimeLeftMillis / 1000) / 60;
        int seconds = (int) (mTimeLeftMillis / 1000) % 60;

        String timeLeftFormatted = String.format(Locale.getDefault(),"%02d:%02d", minutes, seconds);

        mTextViewCountdown.setText(timeLeftFormatted);


    }




}
