package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.app.DialogFragment;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;

import android.support.design.widget.TabLayout;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.TabAdapter;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.MyStartedService;
//import android.widget.Toolbar;


// TODO: pass class name and change toolbar name


public class StudentClassPage extends AppCompatActivity implements ActiveSessionDialog.ActiveSessionDialogListener {

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

        // TODO: Check server if there is a question active
//        if (activeSession) {
//            DialogFragment dialogFragment = new ActiveSessionDialog();
//            dialogFragment.show(getFragmentManager(), "SessionActive");
//        }

    }




    private void setupViewPager(ViewPager viewPager){
        TabAdapter adapter = new TabAdapter(getSupportFragmentManager());
        adapter.addFragment(new StudentClassPageTab1Sessionlist(), getResources().getString(R.string.student_class_page_tab1_session));
        adapter.addFragment(new StudentClassPageTab2Classinfo(), getResources().getString(R.string.student_class_page_tab2_classinfo));
        viewPager.setAdapter(adapter);
    }



    public String getClassName() {
        return classTitle;
    }

    public String getClassId(){ return classId; }



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


        startActivity(intent);

    }

    @Override
    public void onNegativeClick(){
        //Toast.makeText(getApplicationContext(), "Hello from onNegativeClick", Toast.LENGTH_LONG).show();
    }

}
