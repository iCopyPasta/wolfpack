package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.drawable.ColorDrawable;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.SimpleCursorAdapter;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;


public class StudentSessionCompletePage extends SessionPage {

    public static final String TAG = "SSeshCmplt";

    @SuppressLint("SetTextI18n")
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_session_page);


        try{
            Bundle bundle = getIntent().getExtras();

            className = bundle.getString(getString(R.string.KEY_CLASS_DESCRIPTION));
            sessionId = bundle.getString(getString(R.string.KEY_SESSION_ID));
            sessionName = bundle.getString(getString(R.string.KEY_SESSION_NAME));
            sessionStartDate = bundle.getString(getString(R.string.KEY_SESSION_START_DATE));


            getSupportActionBar().setTitle(className);
            mTextViewSessionName.setText(sessionName + " - " + sessionStartDate);
            mListViewQuestionList.setVisibility(View.VISIBLE);
            mTextViewActiveQuestionNotice.setVisibility(View.GONE);

            setupListAdapter();
            setupListView();

        }
        catch (NullPointerException e){
            Log.i(TAG, e.toString());
            throw e;
        }


    }


    private void setupListAdapter(){
        adapter = new SimpleCursorAdapter(this,
                R.layout.listview_question_list,
                null,
                new String[] {"description", "question_type"},
                new int[] {1, 2},
                0);
    }

    private void setupListView(){
        mListViewQuestionList.setAdapter(adapter);

        mListViewQuestionList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Cursor c = adapter.getCursor();

                if (c.moveToPosition(position)) {
                    Intent intent = new Intent(getApplicationContext(), StudentQuestionCompletePage.class);

                    intent.putExtra(getString(R.string.KEY_CLASS_DESCRIPTION), className);

                    intent.putExtra(getString(R.string.KEY_SESSION_ID), sessionId);
                    intent.putExtra(getString(R.string.KEY_SESSION_NAME), sessionName);
                    intent.putExtra(getString(R.string.KEY_SESSION_START_DATE), sessionStartDate);

                    intent.putExtra(getString(R.string.KEY_QUESTION_ID), c.getColumnIndex("question_id"));
                    intent.putExtra(getString(R.string.KEY_QUESTION_TYPE), c.getColumnIndex("question_type"));
                    intent.putExtra(getString(R.string.KEY_QUESTION_DESCRIPTION), c.getColumnIndex("description"));
                    intent.putExtra(getString(R.string.KEY_QUESTION_POTENTIAL_ANSWERS), c.getColumnIndex("potential_answers"));
                    intent.putExtra(getString(R.string.KEY_QUESTION_CORRECT_ANSWERS), c.getColumnIndex("correct_answers"));
                    intent.putExtra(getString(R.string.KEY_QUESTION_STUDENT_ANSWERS), c.getColumnIndex("student_answers"));

                    startActivity(intent);
                }

            }
        });

    }



    @SuppressLint("StaticFieldLeak")
    public void loadQuestionList(){
        new AsyncTask<Void, Void, Cursor>(){

            @Override
            protected Cursor doInBackground(Void... params) {
                String[] projection = {"question_id", "description", "question_type", "potential_answers", "correct_answers", "student_answers"};
                String table = getString(R.string.TABLE_Q_IS_IN) + ", " + getString(R.string.TABLE_QUESTION);
                String selection = "session_id = ? AND question_id = _id";
                String[] selectionArgs = {String.valueOf(sessionId)};
                String sortOrder = "question_id DESC";
                return db.query(
                        table,
                        projection,
                        selection,
                        selectionArgs,
                        null,
                        null,
                        sortOrder
                );
            }

            @Override
            protected void onPostExecute(Cursor cursor){
                adapter.swapCursor(cursor);
            }

        }.execute();
    }







}
