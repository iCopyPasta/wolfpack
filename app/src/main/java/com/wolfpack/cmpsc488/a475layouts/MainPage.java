package com.wolfpack.cmpsc488.a475layouts;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.sqlite.SQLiteDatabase;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;

import com.wolfpack.cmpsc488.a475layouts.experiences.student.StudentClassPage;
import com.wolfpack.cmpsc488.a475layouts.experiences.student.StudentPage;
import com.wolfpack.cmpsc488.a475layouts.experiences.teacher.TeacherPage;

import com.wolfpack.cmpsc488.a475layouts.services.authentication.LoginPage;
import com.wolfpack.cmpsc488.a475layouts.services.authentication.SignUp;
import com.wolfpack.cmpsc488.a475layouts.services.sqlite_database.PollatoDB;


public class MainPage extends AppCompatActivity {

    public static final String TAG = "MainPage";
    public static final String USER_MODE_TEACHER = "TEACHER";
    public static final String USER_MODE_STUDENT = "STUDENT";
    public static final String BUTTON_CALLED = "BUTTON_CALLED";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main_page);
    }

    protected void onPause() {
        super.onPause();
    }

    protected void onStop() {
        super.onStop();
    }

    @Override
    protected void onStart() {
        super.onStart();
        Context context = this;
        //context.deleteDatabase(getString(R.string.DATABASE_NAME));

        SharedPreferences sharedPref = context.getSharedPreferences(
                getString(R.string.preference_file_key), Context.MODE_PRIVATE);

        String mode = sharedPref.getString(getString(R.string.USER_MODE), "none");
        Boolean loggedIn = sharedPref.getBoolean(getString(R.string.SKIP_LOGIN), false);

        Log.i(TAG, "loggedIn = " + loggedIn);

        if(loggedIn){
            if(mode.equals(USER_MODE_TEACHER))
                //TODO: add link to teacher landing page for android
                //Intent intent = new Intent(this, SOMETHING.class);
                //startActivity(intent);
                ;
            if(mode.equals(USER_MODE_STUDENT)){
                Intent intent = new Intent(this, StudentPage.class);
                startActivity(intent);
                finish();
            }
        }
    }

    @Override
    protected void onDestroy() { super.onDestroy(); }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
    }

    public void onLogin(View view){
        Log.i(TAG, "onLogin is called");

        int id = view.getId();

        Intent intent = new Intent(this, LoginPage.class);

        switch(id){
            /*case R.id.teacherSigninButton:
                intent.putExtra(BUTTON_CALLED, USER_MODE_TEACHER);
                break;*/
            case R.id.studentSigninButton:
                intent.putExtra(BUTTON_CALLED, USER_MODE_STUDENT);
                break;
        }
        startActivity(intent);
    }

    public void onSignUp(View view){
        Log.i(TAG, "onSignup is called");
        Intent intent = new Intent(this, SignUp.class);
        startActivity(intent);
    }


    public void onNADemo(View view){
        Log.i("Main Page", "onNADemo is called");
        Intent intent = new Intent(this, CameraExample.class);
        startActivity(intent);
    }

    /* MUST REMOVE LATER */
    public void onToStudent(View view){
        Log.i("Main Page", "onToStudent is called");
        Intent intent = new Intent(this, StudentPage.class);
        startActivity(intent);
    }

    /*public void onToTeacher(View view){
        Log.i("Main Page", "on??is called");
        Intent intent = new Intent(MainPage.this, StudentClassPage.class);
        intent.putExtra("className", "Formal Languages");
        //TODO: USED ONLY FOR TESTING
        intent.putExtra("classId", "336");

        startActivity(intent);

    }*/

    public void onCameraExample(View view){
        Log.i("Main Page", "onCameraExample is called");
        Intent intent = new Intent(this, CameraExample.class);
        startActivity(intent);
    }


    /*public void onToClassPage(View view){
        Log.i("Main Page", "onToClassPage is called");
        Intent intent = new Intent(this, StudentClassPage.class);
        intent.putExtra("className", "AI");
        startActivity(intent);
    }*/


}
