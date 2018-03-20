package com.wolfpack.cmpsc488.a475layouts.services.authentication;

import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.annotation.TargetApi;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v7.app.AppCompatActivity;

import android.os.AsyncTask;

import android.os.Build;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.inputmethod.EditorInfo;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.CameraExample;
import com.wolfpack.cmpsc488.a475layouts.MainPage;
import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.experiences.student.StudentPage;
import com.wolfpack.cmpsc488.a475layouts.services.WolfpackClient;

import retrofit2.Call;
import retrofit2.Response;

/**
 * A login screen that offers login via email/password.
 */
public class LoginPage extends AppCompatActivity {

    public static final String TAG = "LoginPage";

    private UserLoginTask mAuthTask = null;
    private String mode = null;

    // UI references.
    private AutoCompleteTextView mEmailView;
    private EditText mPasswordView;
    private View mProgressView;
    private View mLoginFormView;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login_page);
        // Set up the login form.
        mEmailView = (AutoCompleteTextView) findViewById(R.id.email);

        mPasswordView = (EditText) findViewById(R.id.password);
        mPasswordView.setOnEditorActionListener(new TextView.OnEditorActionListener() {
            @Override
            public boolean onEditorAction(TextView textView, int id, KeyEvent keyEvent) {
                if (id == EditorInfo.IME_ACTION_DONE || id == EditorInfo.IME_NULL) {
                    attemptLogin();
                    Log.i(TAG, "finished with attemptLogin");
                    return true;
                }
                return false;
            }
        });

        Intent intent = getIntent();
        mode = intent.getStringExtra(MainPage.BUTTON_CALLED);

        Button mEmailSignInButton = (Button) findViewById(R.id.email_sign_in_button);
        mEmailSignInButton.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                attemptLogin();
            }
        });

        mLoginFormView = findViewById(R.id.login_form);
        mProgressView = findViewById(R.id.login_progress);

    }

    /**
     * Attempts to sign in or register the account specified by the login form.
     * If there are form errors (invalid email, missing fields, etc.), the
     * errors are presented and no actual login attempt is made.
     */
    private void attemptLogin() {
        if (mAuthTask != null) {
            return;
        }

        // Reset errors.
        mEmailView.setError(null);
        mPasswordView.setError(null);

        // Store values at the time of the login attempt.
        String email = mEmailView.getText().toString();
        String password = mPasswordView.getText().toString();

        boolean cancel = false;
        View focusView = null;

        // Check for a valid password, if the user entered one.
        if (!TextUtils.isEmpty(password) && !isPasswordValid(password)) {
            mPasswordView.setError(getString(R.string.error_invalid_password));
            focusView = mPasswordView;
            cancel = true;
        }

        // Check for a valid email address.
        if (TextUtils.isEmpty(email)) {
            mEmailView.setError(getString(R.string.error_field_required));
            focusView = mEmailView;
            cancel = true;
        } else if (!isEmailValid(email)) {
            mEmailView.setError(getString(R.string.error_invalid_email));
            focusView = mEmailView;
            cancel = true;
        }

        if (cancel) {
            // There was an error; don't attempt login and focus the first
            // form field with an error.
            focusView.requestFocus();
        } else {
            // Show a progress spinner, and kick off a background task to
            // perform the user login attempt.
            showProgress(true);
            mAuthTask = new UserLoginTask();
            mAuthTask.execute(email, password);
        }
    }

    private boolean isEmailValid(String email) {
        //TODO: Replace this with your own logic
        return email.contains("@");
    }

    private boolean isPasswordValid(String password) {
        //TODO: Replace this with your own logic
        //return password.length() > 4;
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        //TODO: FOR TESTING PURPOSES!!!
        return password.length() > 0;
    }

    /**
     * Shows the progress UI and hides the login form.
     */
    @TargetApi(Build.VERSION_CODES.HONEYCOMB_MR2)
    private void showProgress(final boolean show) {
        // On Honeycomb MR2 we have the ViewPropertyAnimator APIs, which allow
        // for very easy animations. If available, use these APIs to fade-in
        // the progress spinner.
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB_MR2) {
            int shortAnimTime = getResources().getInteger(android.R.integer.config_shortAnimTime);

            mLoginFormView.setVisibility(show ? View.GONE : View.VISIBLE);
            mLoginFormView.animate().setDuration(shortAnimTime).alpha(
                    show ? 0 : 1).setListener(new AnimatorListenerAdapter() {
                @Override
                public void onAnimationEnd(Animator animation) {
                    mLoginFormView.setVisibility(show ? View.GONE : View.VISIBLE);
                }
            });

            mProgressView.setVisibility(show ? View.VISIBLE : View.GONE);
            mProgressView.animate().setDuration(shortAnimTime).alpha(
                    show ? 1 : 0).setListener(new AnimatorListenerAdapter() {
                @Override
                public void onAnimationEnd(Animator animation) {
                    mProgressView.setVisibility(show ? View.VISIBLE : View.GONE);
                }
            });
        } else {
            // The ViewPropertyAnimator APIs are not available, so simply show
            // and hide the relevant UI components.
            mProgressView.setVisibility(show ? View.VISIBLE : View.GONE);
            mLoginFormView.setVisibility(show ? View.GONE : View.VISIBLE);
        }
    }

    public void onNADemo(View view){
        Log.i(TAG, "onNADemo is called");
        Intent intent = new Intent(this, CameraExample.class);
        startActivity(intent);
    }

    /**
     * Represents an asynchronous login/registration task used to authenticate
     * the user.
     */
    public class UserLoginTask extends AsyncTask<String, Void, Boolean> {

        LoginDetails loginDetails;

        Response<LoginDetails> response;

        @Override
        protected Boolean doInBackground(String... params) {
            // TODO: attempt authentication against a network service.

            try {
                Log.i(TAG, "About to try network request out");
                // TODO: attempt authentication against a network service.

                WolfpackClient webService =
                        WolfpackClient.retrofit.create(WolfpackClient.class);


                Log.i(TAG, "setting call with parameters");
                Call<LoginDetails> call =
                        webService.attemptLogin(true,"attemptLogin", params[0], params[1]);

                Log.i(TAG, "waiting on potential values");

                //TODO: ADD SECURE TRY-CATCH BLOCKS FOR VARIOUS POSSIBILITIES!
                response = call.execute();
                Log.i(TAG, response.body().toString());
                loginDetails = response.body();
                Log.i("Sign_in", "Finished");

                return loginDetails != null && loginDetails.getStatus() > 0;
            } catch (Exception e){
                Log.e(TAG, e.getMessage());
                return false;
            }

        }

        @Override
        protected void onPostExecute(final Boolean success) {

            String buttonName;

            Intent caller = getIntent();
            Intent intent = null;
            if(caller != null){


                buttonName = caller.getStringExtra(MainPage.BUTTON_CALLED);
                Log.i(TAG, "button name is: "  + buttonName);

                if(buttonName.equals(MainPage.USER_MODE_STUDENT)){
                    intent = new Intent(getApplicationContext(), StudentPage.class);
                }

                if(buttonName.equals(MainPage.USER_MODE_TEACHER)){
                    //intent = new Intent(getApplicationContext(), SOMETHING.class)

                }
            }

            mAuthTask = null;
            showProgress(false);


            if (success) {
                Log.i(TAG, "successful login");

                //SHARED PREFERENCES UPDATE
                Context context = getApplicationContext();
                SharedPreferences sharedPref = context.getSharedPreferences(
                        getString(R.string.preference_file_key), Context.MODE_PRIVATE);

                SharedPreferences.Editor editor = sharedPref.edit();
                editor.putBoolean(getString(R.string.SKIP_LOGIN), true);
                editor.putString(getString(R.string.USER_MODE), mode);
                editor.putString(getString(R.string.USER_EMAIL), mEmailView.getText().toString());

                Log.i(TAG, "email entered = "+mEmailView.getText().toString());

                editor.apply(); //dedicate to persistant storage in background thread

                //FEEDBACK FROM SERVER
                String message = loginDetails.getMessage();

                Toast.makeText(LoginPage.this, message, Toast.LENGTH_SHORT).show();

                if(intent != null)
                    startActivity(intent);



            } else {
                mPasswordView.setError(getString(R.string.error_incorrect_password));
                mPasswordView.requestFocus();
            }
        }

        @Override
        protected void onCancelled() {
            mAuthTask = null;
            showProgress(false);
        }
    }
}

