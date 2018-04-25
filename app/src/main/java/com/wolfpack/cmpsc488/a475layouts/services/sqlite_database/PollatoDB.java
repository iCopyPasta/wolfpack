package com.wolfpack.cmpsc488.a475layouts.services.sqlite_database;

import android.annotation.SuppressLint;
import android.content.AsyncTaskLoader;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.os.AsyncTask;
import android.util.Log;


public class PollatoDB extends SQLiteOpenHelper {

    public static final String TAG = "PollatoDB";

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


    private static final String SQL_CREATE_TABLE_SESSION =
            "CREATE TABLE session (" +
                    "_id INTEGER PRIMARY KEY AUTOINCREMENT,"+
                    "class_id INTEGER NOT NULL,"+
                    "name TEXT NOT NULL,"+
                    "start_date TEXT)";

    private static final String SQL_CREATE_TABLE_QUESTION =
            "CREATE TABLE question ("+
                    "_id INTEGER PRIMARY KEY AUTOINCREMENT,"+
                    "question_type TEXT NOT NULL,"+
                    "description TEXT NOT NULL,"+
                    "potential_answers BLOB NOT NULL,"+
                    "correct_answers BLOB NOT NULL)";

//    private static final String SQL_CREATE_TABLE_ANSWER =
//            "CREATE TABLE answers ("+
//                    "_id INTEGER PRIMARY KEY AUTOINCREMENT,"+
//                    "student_answers BLOB)";

    private static final String SQL_CREATE_TABLE_QUESTION_IS_IN =
            "CREATE TABLE question_is_in (" +
                    "_id INTEGER PRIMARY KEY AUTOINCREMENT," +
                    "session_id INTEGER,"+
                    "question_id INTEGER,"+
                    "student_answer BLOB," +
                    "FOREIGN KEY (session_id) REFERENCES session (_id),"+
                    "FOREIGN KEY (question_id) REFERENCES question (_id))";
//                    "FOREIGN KEY (answer_id) REFERENCES answer(_id))";


    private static final String SQL_DROP_TABLE_SESSION =
            "DROP TABLE IF EXISTS session";

    private static final String SQL_DROP_TABLE_QUESTION =
            "DROP TABLE IF EXISTS question";

//    private static final String SQL_DROP_TABLE_ANSWER =
//            "DROP TABLE IF EXISTS answers";

    private static final String SQL_DROP_TABLE_QUESTION_IS_IN =
            "DROP TABLE IF EXISTS question_is_in";


    @Override
    public void onCreate(SQLiteDatabase db) {
        db.execSQL(SQL_CREATE_TABLE_SESSION);
        db.execSQL(SQL_CREATE_TABLE_QUESTION);
//        db.execSQL(SQL_CREATE_TABLE_ANSWER);
        db.execSQL(SQL_CREATE_TABLE_QUESTION_IS_IN);
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL(SQL_DROP_TABLE_QUESTION_IS_IN);
//        db.execSQL(SQL_DROP_TABLE_ANSWER);
        db.execSQL(SQL_DROP_TABLE_QUESTION);
        db.execSQL(SQL_DROP_TABLE_SESSION);
        onCreate(db);
    }

    @Override
    public void onDowngrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        onUpgrade(db, oldVersion, newVersion);
    }


    @SuppressLint("StaticFieldLeak")
    public static void printDatabase(){
        new AsyncTask<Void, Void, Void>() {

            @Override
            protected Void doInBackground(Void... voids) {
                try{
                    SQLiteDatabase db = PollatoDB.instance.getWritableDatabase();

                    Log.w(TAG, "displaying db contents");
                    Cursor c1 = db.rawQuery("SELECT * FROM session", new String[]{});
                    Cursor c2 = db.rawQuery("SELECT * FROM question", new String[]{});
                    Cursor c3 = db.rawQuery("SELECT * FROM question_is_in", new String[]{});
                    Log.w(TAG, " ");
                    Log.w(TAG, "session: _id | class_id | name | start_date");
                    while (c1.moveToNext())
                    {
                        Log.w(TAG,
                                c1.getInt(0) + " | " +
                                        c1.getString(1) + " | " +
                                        c1.getString(2) + " | " +
                                        c1.getString(3));
                    }

                    Log.w(TAG, " ");
                    Log.w(TAG, "question: _id | question_type | description | potential_answers | correct_answers");
                    while (c2.moveToNext())
                    {
                        Log.w(TAG,
                                c2.getInt(0) + " | " +
                                        c2.getString(1) + " | " +
                                        c2.getString(2) + " | " +
                                        c2.getString(3) + " | " +
                                        c2.getString(4));
                    }
                    Log.w(TAG, " ");
                    Log.w(TAG, "question_is_in: _id | question_id | session_id | student_answer");
                    while (c3.moveToNext())
                    {
                        Log.w(TAG,
                                c3.getInt(0) + " | " +
                                        c3.getString(1) + " | " +
                                        c3.getString(2) + " | " +
                                        c3.getString(3));
                    }
                    Log.w(TAG, " ");

                    c1.close();
                    c2.close();
                    c3.close();
                }
                catch(Exception e){
                    return null;
                }


                return null;
            }
        }.execute();
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
            if (isGetWritable) return PollatoDB.instance.getWritableDatabase();
            else               return PollatoDB.instance.getReadableDatabase();
        }

        @Override
        protected void onPostExecute(SQLiteDatabase db){
            listener.onDBReady(db);
        }
    }













}
