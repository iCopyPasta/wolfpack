package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.annotation.SuppressLint;
import android.app.DialogFragment;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.sqlite.SQLiteDatabase;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;

import android.support.design.widget.TabLayout;
import android.view.animation.AccelerateDecelerateInterpolator;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.TabAdapter;
import com.wolfpack.cmpsc488.a475layouts.services.WolfpackClient;
import com.wolfpack.cmpsc488.a475layouts.services.data_retrieval.BasicWolfpackResponse;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyStartedService;
import com.wolfpack.cmpsc488.a475layouts.services.sqlite_database.PollatoDB;

import java.io.IOException;

import retrofit2.Call;
//import android.widget.Toolbar;


// TODO: pass class name and change toolbar name


public class StudentClassPage extends AppCompatActivity
        implements
        ActiveSessionDialog.ActiveSessionDialogListener,
        AlertDropDialog.AlertDropDialogListener {


    public static final String TAG = "StudentClassPage";

    public String studentId;
    public String classId;
    public String classTitle;
    public String classDesc;
    public String classOffering;
    public String classLocation;
    public String teacherName;

    private Toolbar classTitleDisplay;

    private TabAdapter mTabAdapter;
    private ViewPager mViewPage;


    // TODO: set to false and assign activeSession based on if there is an active session currently
    boolean activeSession = true;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_student_class_page);


        Bundle bundle;
        try {
            bundle = getIntent().getExtras();

            //get information
            studentId = bundle.getString(getString(R.string.KEY_STUDENT_ID));
            classId = bundle.getString(getString(R.string.KEY_CLASS_ID));
            classTitle = bundle.getString(getString(R.string.KEY_CLASS_TITLE));
            classDesc = bundle.getString(getString(R.string.KEY_CLASS_DESCRIPTION));
            classOffering = bundle.getString(getString(R.string.KEY_CLASS_OFFERING));
            classLocation = bundle.getString(getString(R.string.KEY_CLASS_LOCATION));
            teacherName = bundle.getString(getString(R.string.KEY_CLASS_TEACHER_NAME));

            Log.d(TAG, "in try before classTitleDisplay assignment");

            //displays class name (eg CMPSC 121) in the toolbar
            classTitleDisplay = (Toolbar) findViewById(R.id.studentToolbarClassPage);
            setSupportActionBar(classTitleDisplay);
            classTitleDisplay.setTitle(classTitle);

            Log.i(TAG, "classTitle = " + classTitle);

            //setup up viewpager (set up transition between tabs)
            mViewPage = (ViewPager) findViewById(R.id.studentClassPageViewPager);
            setupViewPager(mViewPage);

            TabLayout tabLayout = (TabLayout) findViewById(R.id.studentClassPageTabs);
            tabLayout.setupWithViewPager(mViewPage);


            Log.d(TAG, "end of onCreate");
        }
        catch (NullPointerException e){
            Log.d(TAG,"StudentClassPage got NullPointerException");
            Log.d(TAG, e.getMessage());
            throw e;
        }

    }



    //TODO: move this to an async task (created in handleCompletedSession)
    @Override
    protected void onResume() {
        super.onResume();
    }

    @Override
    protected void onDestroy(){
        super.onDestroy();
        stopService(new Intent(StudentClassPage.this, MyStartedService.class));
    }

    private void setupViewPager(ViewPager viewPager){
        TabAdapter adapter = new TabAdapter(getSupportFragmentManager());
        adapter.addFragment(new StudentClassPageTab1Sessionlist(), getResources().getString(R.string.student_class_page_tab1_session));
        adapter.addFragment(new StudentClassPageTab2Classinfo(), getResources().getString(R.string.student_class_page_tab2_classinfo));
        viewPager.setAdapter(adapter);
    }



    public String getClassId()   { return classId; }
    public String getClassName() { return classTitle; }



    /**
     * ActiveSessionDialog.ActiveSessionDialogListener function implementation
     */

    @Override
    public void onPositiveClick(Bundle info){
        Log.i(TAG, "onPositiveClick called, moving to StudentSessionPage" );

        Intent intent = new Intent(StudentClassPage.this, StudentSessionActivePage.class);
        intent.putExtra("classTitle", "Test Class");
        intent.putExtra("sessionName", "Active Session");
        intent.putExtra("isActive", true);
        intent.putExtra("classId", classId);


        intent.putExtra(MyStartedService.MY_SERVICE_QUESTION_SET_ID,
                info.getString(MyStartedService.MY_SERVICE_QUESTION_SET_ID));

        intent.putExtra(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID,
                info.getString(MyStartedService.MY_SERVICE_QUESTION_SESSION_ID));

        intent.putExtra(MyStartedService.MY_SERVICE_QUESTION_SET_NAME,
            info.getString(MyStartedService.MY_SERVICE_QUESTION_SET_NAME));


        startActivity(intent);

    }

    @Override
    public void onNegativeClick(){}

    @SuppressLint("StaticFieldLeak")
    @Override
    public void onDropPositiveClick() {
        DropBackgroundTask task = new DropBackgroundTask();
        task.execute();

    }

    @Override
    public void onDropNegativeClick() {}

    public class DropBackgroundTask extends AsyncTask<String, Void, Boolean>{

        String student_id = null;
        String class_id = null;

        @Override
        protected void onPreExecute(){
            //SHARED PREFERENCES UPDATE
            SharedPreferences sharedPref = getSharedPreferences(
                    getString(R.string.preference_file_key), Context.MODE_PRIVATE);

            student_id = sharedPref.getString(getString(R.string.STUDENT_ID),"");
            class_id = classId;
        }


        @Override
        protected Boolean doInBackground(String... params) {

            WolfpackClient client = WolfpackClient.retrofit.create(WolfpackClient.class);
            BasicWolfpackResponse response;

            Call<BasicWolfpackResponse> result = client.dropClass(
                    student_id,
                    class_id,
                    "dropClass");

            try {
                 response = result.execute().body();
            } catch (IOException e) {
                Log.e(TAG, "doInBackground: IOException: " + e.getMessage());
                return false;
            } catch(Exception e){
                Log.e(TAG, "doInBackground: " + e.getMessage());
                return false;
            }

            return response != null && response.getStatus() > 0;
        }

        @Override
        protected void onPostExecute(final Boolean result){
            if(result){
                finish();
            }


        }
    }


}
