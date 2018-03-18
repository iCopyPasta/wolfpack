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
//import android.widget.Toolbar;


// TODO: pass class name and change toolbar name


public class StudentClassPage extends AppCompatActivity implements ActiveSessionDialog.ActiveSessionDialogListener {

    public static final String TAG = "StudentClassPage";

    private String className;
    private Toolbar classNameDisplay;


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
            //get class name
            bundle = getIntent().getExtras();
            className = (String) bundle.get("className");

            Log.i(TAG, "in try before classNameDisplay assignment");

            //displays class name (eg CMPSC 121) in the toolbar
            classNameDisplay = (Toolbar) findViewById(R.id.studentToolbarClassPage);
            setSupportActionBar(classNameDisplay);
            classNameDisplay.setTitle(className);

            Log.i(TAG, "className = " + className);

            //setup up viewpager (set up transition between tabs)
            mViewPage = (ViewPager) findViewById(R.id.studentClassPageViewPager);
            setupViewPager(mViewPage);

            TabLayout tabLayout = (TabLayout) findViewById(R.id.studentClassPageTabs);
            tabLayout.setupWithViewPager(mViewPage);


            Log.i(TAG, "end of onCreate");
        }
        catch (NullPointerException e){
            Log.i(TAG,"StudentClassPage got NullPointerException");
            Log.i(TAG, e.getMessage());
        }

    }



    //TODO: move this to an async task (created in handleCompletedSession)
    @Override
    protected void onResume() {
        super.onResume();

        // TODO: Check server if there is a question active
        if (activeSession) {
            DialogFragment dialogFragment = new ActiveSessionDialog();
            dialogFragment.show(getFragmentManager(), "SessionActive");
        }

    }




    private void setupViewPager(ViewPager viewPager){
        TabAdapter adapter = new TabAdapter(getSupportFragmentManager());
        adapter.addFragment(new StudentClassPageTab1Sessionlist(), getResources().getString(R.string.student_class_page_tab1_session));
        adapter.addFragment(new StudentClassPageTab2Classinfo(), getResources().getString(R.string.student_class_page_tab2_classinfo));
        viewPager.setAdapter(adapter);
    }



    public String getClassName() {
        return className;
    }



    /**
     * ActiveSessionDialog.ActiveSessionDialogListener function implementation
     */

    @Override
    public void onPositiveClick(){
        //Toast.makeText(getApplicationContext(), "Hello from onPositiveClick", Toast.LENGTH_LONG).show();

        //send user to an active session's page
        Intent intent = new Intent(getApplicationContext(), StudentSessionPage.class);
        intent.putExtra("className", className);
        //TODO: decide who gets the session name
        //intent.putExtra("sessionName", "");
        intent.putExtra("isActive", true);
        startActivity(intent);

    }

    @Override
    public void onNegativeClick(){
        //Toast.makeText(getApplicationContext(), "Hello from onNegativeClick", Toast.LENGTH_LONG).show();
    }

}
