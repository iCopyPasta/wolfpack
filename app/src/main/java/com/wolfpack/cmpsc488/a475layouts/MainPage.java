package com.wolfpack.cmpsc488.a475layouts;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

public class MainPage extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main_page);

        //TODO: Add logic in order to skip MainPage if the user already authenticated
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
    protected void onDestroy() { super.onDestroy(); }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
    }

    public void onLogin(View view){
        //quick access to demo student page
        //Intent intent = new Intent(this, StudentPage.class);

        Intent intent = new Intent(this, LoginPage.class);

        /*
        Button b = (Button) view;

        //if(b.getBack)
        intent.putExtra("color", b.getDrawingCacheBackgroundColor());
        */


        startActivity(intent);
    }

}
