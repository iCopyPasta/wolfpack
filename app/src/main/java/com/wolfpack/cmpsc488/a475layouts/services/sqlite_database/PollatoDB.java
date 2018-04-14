package com.wolfpack.cmpsc488.a475layouts.services.sqlite_database;

import android.content.AsyncTaskLoader;
import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.os.AsyncTask;


public class PollatoDB extends SQLiteOpenHelper {

    public interface OnDBReadyListener{
        void onDBReady(SQLiteDatabase db);
    }

    public static final int DATABASE_VERSION = 1;
    public static final String DATABASE_NAME = "pollato.db";

    private static PollatoDB instance;


    private PollatoDB(Context context){
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    public static synchronized PollatoDB getInstance(Context context){
        if (instance == null){
            instance = new PollatoDB(context);
        }
        return instance;
    }


    private static final String SQL_CREATE_TABLES =
            "CREATE TABLE session (" +
                    "_id INTEGER PRIMARY KEY AUTOINCREMENT,"+
                    "class_id INTEGER NOT NULL,"+
                    "name TEXT NOT NULL,"+
                    "start_date TEXT);"+
            "CREATE TABLE question ("+
                    "_id INTEGER PRIMARY KEY AUTOINCREMENT,"+
                    "teacher_id INTEGER NOT NULL,"+
                    "question_type TEXT NOT NULL,"+
                    "description TEXT NOT NULL,"+
                    "potential_answers BLOB NOT NULL,"+
                    "correct_answers BLOB NOT NULL,"+
                    "student_answers BLOB);"+
            "CREATE TABLE question_is_in (" +
                    "question_id INTEGER PRIMARY KEY,"+
                    "session_id INTEGER,"+
                    "FOREIGN KEY (question_id) REFERENCES question (_id)"+
                    "FOREIGN KEY (session_id) REFERENCES session (_id));";


    private static final String SQL_DROP_TABLES =
            "DROP TABLE IF EXISTS question_is_in;"+
            "DROP TABLE IF EXISTS question;"+
            "DROP TABLE IF EXISTS session;";


    @Override
    public void onCreate(SQLiteDatabase db) {
        db.execSQL(SQL_CREATE_TABLES);
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL(SQL_DROP_TABLES);
        onCreate(db);
    }

    @Override
    public void onDowngrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        onUpgrade(db, oldVersion, newVersion);
    }



    public void getWritableDatabase(OnDBReadyListener listener) {
        new OpenDBAsyncTask(listener, true).execute();
    }

    public void getReadableDatabase(OnDBReadyListener listener){
        new OpenDBAsyncTask(listener, false).execute();
    }


    private static class OpenDBAsyncTask
            extends AsyncTask<Void, Void, SQLiteDatabase>{

        OnDBReadyListener listener;
        boolean isGetWritable;

        private OpenDBAsyncTask(OnDBReadyListener listener, boolean isGetWritable){
            this.listener = listener;
            this.isGetWritable = isGetWritable;
        }

        @Override
        protected SQLiteDatabase doInBackground(Void... params) {
            if (isGetWritable)
                return PollatoDB.instance.getWritableDatabase();
            else
                return PollatoDB.instance.getReadableDatabase();
        }

        @Override
        protected void onPostExecute(SQLiteDatabase db){
            listener.onDBReady(db);
        }
    }













}
