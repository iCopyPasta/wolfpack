package com.wolfpack.cmpsc488.a475layouts;

import android.app.DialogFragment;
import android.media.session.MediaSessionManager;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.widget.Toast;


// TODO: pass class name and change toolbar name


public class SessionPage extends AppCompatActivity implements ActiveSessionDialog.ActiveSessionDialogListener {

    public static final String TAG = "SessionPage";


    // TODO: set to false and assign activeSession based on if there is an active session currently
    boolean activeSession = true;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_class_session_page);

        Bundle bundle = getIntent().getExtras();
        if (bundle != null){
            Log.i(TAG,"SessionPage got getIntent().getExtras() successfully");
        }


    }


    @Override
    protected void onResume() {
        super.onResume();

        // TODO: Check server if there is a question active
        if (activeSession) {
            DialogFragment dialogFragment = new ActiveSessionDialog();
            dialogFragment.show(getFragmentManager(), "what is this text?");
        }

    }


    /**
     * ActiveSessionDialog.ActiveSessionDialogListener function implementation
     */

    @Override
    public void onPositiveClick(){
        // TODO: take user to (active) question page
        Toast.makeText(getApplicationContext(), "Hello from onPositiveClick", Toast.LENGTH_LONG).show();
    }

    @Override
    public void onNegativeClick(){
        // TODO: keep user on session page
        Toast.makeText(getApplicationContext(), "Hello from onNegativeClick", Toast.LENGTH_LONG).show();
    }

}
