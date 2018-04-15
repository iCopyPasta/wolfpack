package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.database.sqlite.SQLiteDatabase;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.widget.ListView;
import android.widget.SimpleCursorAdapter;
import android.widget.TextView;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.services.sqlite_database.PollatoDB;


public abstract class SessionPage extends AppCompatActivity {

    public static final String TAG = "SessionPage";

    //database
    protected SQLiteDatabase db;

    //UI elements
    protected TextView mTextViewSessionName;
    protected ListView mListViewQuestionList;
    protected TextView mTextViewActiveQuestionNotice;
    protected SimpleCursorAdapter adapter;

    //Information elements
    protected String className = "";
    protected String sessionId = "";
    protected String sessionName = "";
    protected String sessionStartDate = "";


    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_session_page);

        try{
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.colorStudentPrimary)));

            Log.i(TAG, "super class onCreate");

            mTextViewSessionName = findViewById(R.id.sessionNameTextView);
            mListViewQuestionList = findViewById(R.id.questionListListView);
            mTextViewActiveQuestionNotice = findViewById(R.id.activeQuestionNoticeTextView);

            PollatoDB.getInstance(this).getWritableDatabase(
                    new PollatoDB.OnDBReadyListener() {
                        @Override
                        public void onDBReady(SQLiteDatabase db) {
                            SessionPage.this.db = db;
                            loadQuestionList();
                        }
                    }
            );

        }
        catch (NullPointerException e){
            Log.i(TAG, e.getMessage());
            throw e;
        }

    }


    protected abstract void loadQuestionList();






}
