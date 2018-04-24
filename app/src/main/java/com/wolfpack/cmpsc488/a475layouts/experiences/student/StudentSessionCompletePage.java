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


    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        //setContentView(R.layout.activity_session_page);

        Log.d(TAG, "onCreate StudentSessionCompletePage");

        try{

            Bundle bundle = getIntent().getExtras();

            sessionId = bundle.getString(getString(R.string.KEY_SESSION_ID));
            sessionName = bundle.getString(getString(R.string.KEY_SESSION_NAME));
            sessionStartDate = bundle.getString(getString(R.string.KEY_SESSION_START_DATE));

            Log.i(TAG, "classId = " + classId + "\n" +
                    "className = " + className + "\n" +
                    "sessionId = " + sessionId + "\n" +
                    "sessionName = " + sessionName + "\n" +
                    "sessionStartDate = " + sessionStartDate);

            Log.i("I'm not crazy:", sessionName + " - " + sessionStartDate);
            String titleText = sessionName + " - " + sessionStartDate;

            mTextViewSessionName.setText(titleText);
            mListViewQuestionList.setVisibility(View.VISIBLE);
            mTextViewActiveQuestionNotice.setVisibility(View.GONE);
            //mProgressBar.setVisibility(View.VISIBLE);

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
                new int[] {R.id.questionDescriptionTextView, R.id.questionTypeTextView},
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

                    intent.putExtra(getString(R.string.KEY_CLASS_ID), classId);
                    intent.putExtra(getString(R.string.KEY_CLASS_TITLE), className);

                    intent.putExtra(getString(R.string.KEY_SESSION_ID), sessionId);
                    intent.putExtra(getString(R.string.KEY_SESSION_NAME), sessionName);
                    intent.putExtra(getString(R.string.KEY_SESSION_START_DATE), sessionStartDate);

                    intent.putExtra(getString(R.string.KEY_QUESTION_ID), String.valueOf(c.getInt(0)));
                    intent.putExtra(getString(R.string.KEY_QUESTION_TYPE), c.getString(1));
                    intent.putExtra(getString(R.string.KEY_QUESTION_DESCRIPTION), c.getString(2));
                    intent.putExtra(getString(R.string.KEY_QUESTION_POTENTIAL_ANSWERS), c.getString(3));
                    intent.putExtra(getString(R.string.KEY_QUESTION_CORRECT_ANSWERS), c.getString(4));
                    intent.putExtra(getString(R.string.KEY_QUESTION_STUDENT_ANSWERS), c.getString(5));

                    startActivity(intent);
                }
            }
        });

    }



    @SuppressLint("StaticFieldLeak")
    public void loadQuestionList(){
        new AsyncTask<Void, Void, Cursor>(){

            @Override
            protected void onPreExecute(){
                mProgressBar.setVisibility(View.VISIBLE);
            }

            @Override
            protected Cursor doInBackground(Void... params) {
                String projection = "question._id, question_type, description, potential_answers, correct_answers, student_answer";
                //String table = getString(R.string.TABLE_Q_IS_IN) + " " + getString(R.string.TABLE_QUESTION) + " ON " + ;
                //String selection = "session_id = ? AND question_id = _id";
                String[] selectionArgs = {String.valueOf(sessionId)};
                //String sortOrder = "question_id DESC";

                return db.rawQuery(
                        "WITH question_link as (SELECT * FROM question_is_in WHERE session_id = ?) " +
                                " SELECT " + projection +
                                " FROM " + " question_link CROSS JOIN question " +
                                " WHERE " + " question_link.question_id = question._id " +
                                " ORDER BY question_link._id DESC ",
                        selectionArgs);
            }

            @Override
            protected void onPostExecute(Cursor cursor) {

                while(cursor.moveToNext()){
                    Log.w(TAG, "h\n\n\n\n");
                    Log.w(TAG, "_id = " + cursor.getInt(0));
                    Log.w(TAG, "question_type = " + cursor.getString(1));
                    Log.w(TAG, "description = " + cursor.getString(2));
                    Log.w(TAG, "potential = " + cursor.getString(3));
                    Log.w(TAG, "correct = " + cursor.getString(4));
                    Log.w(TAG, "student = " + cursor.getString(5));

                }


                adapter.swapCursor(cursor);
                mProgressBar.setVisibility(View.GONE);
            }

        }.execute();
    }







}
