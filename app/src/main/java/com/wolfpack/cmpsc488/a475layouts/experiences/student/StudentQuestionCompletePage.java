package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.Locale;
import java.util.concurrent.TimeUnit;

public class StudentQuestionCompletePage extends AppCompatActivity implements ActiveSessionDialog.ActiveSessionDialogListener {
    public static final String TAG = "SQuestionCompletePage";


    private TextView mTextViewQuestion;
    private ListView mListViewAnswers;
    private TextView mTextViewCountdown;

    private String defaultQuestion = "Rick Astley's never gonna:";
    private String[] defaultAnswers = {"Give you up", "Let you down", "Make you cry", "Hurt you"};

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_question_page);

        getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.colorStudentPrimary)));


        // TODO: get question/answers/timer from server
        mTextViewQuestion = findViewById(R.id.questionTextView);

        mTextViewQuestion.setText(defaultQuestion);

        ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(
                getApplicationContext(),
                android.R.layout.simple_list_item_1,
                defaultAnswers);

        mListViewAnswers.setAdapter(mAdapter);

        mListViewAnswers.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
                Toast.makeText(getApplicationContext(),
                        "You Chose: " + defaultAnswers[position],
                        Toast.LENGTH_SHORT).show();
            }
        });

    }













    /**
     * ActiveSessionDialog.ActiveSessionDialogListener function implementation
     */

    @Override
    public void onPositiveClick() {

    }

    @Override
    public void onNegativeClick() {

    }



}
