package com.wolfpack.cmpsc488.a475layouts;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;

public class MainActivity extends AppCompatActivity {
    public static final String TAG = "MainActivity";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        //TODO: Add logic in order to skip MainActivity if the user already authenticated
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
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
    }

    public void onLogin(View view){
        Intent intent = new Intent(this, LoginActivity.class);
        startActivity(intent);
    }


    public void onNADemo(View view){
        Log.i(TAG, "onNADemo is called");
        Intent intent = new Intent(this, CameraExample.class);
        startActivity(intent);
    }

}
