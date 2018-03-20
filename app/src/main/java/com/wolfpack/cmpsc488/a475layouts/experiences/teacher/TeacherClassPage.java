package com.wolfpack.cmpsc488.a475layouts.experiences.teacher;

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


public class TeacherClassPage extends AppCompatActivity {//implements ActiveSessionDialog.ActiveSessionDialogListener {

    public static final String TAG = "TeacherClassPage";

    private String className;
    private Toolbar classNameDisplay;


    private TabAdapter mTabAdapter;
    private ViewPager mViewPage;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_teacher_class_page);


        Bundle bundle;
        try {
            //get class name
            bundle = getIntent().getExtras();
            className = (String) bundle.get("ClassName");

            Log.i(TAG, "in try before classNameDisplay assignment");

            //displays class name (eg CMPSC 121) in the toolbar
            classNameDisplay = (Toolbar) findViewById(R.id.teacherToolbarClassPage);
            setSupportActionBar(classNameDisplay);
            classNameDisplay.setTitle(className);

            Log.i(TAG, "className = " + className);

            //setup up viewpager (set up transition between tabs)
            mViewPage = (ViewPager) findViewById(R.id.teacherClassPageViewPager);
            setupViewPager(mViewPage);

            TabLayout tabLayout = (TabLayout) findViewById(R.id.teacherSessionPageTabs);
            tabLayout.setupWithViewPager(mViewPage);


            Log.i(TAG, "end of onCreate");
        }
        catch (NullPointerException e){
            Log.i(TAG,"StudentClassPage got NullPointerException");
            Log.i(TAG, e.getMessage());
        }

    }


    /*@Override
    protected void onResume() {
        super.onResume();

        // TODO: Check server if there is a question active
        if (activeSession) {
            DialogFragment dialogFragment = new ActiveSessionDialog();
            dialogFragment.show(getFragmentManager(), "SessionActive");
        }

    }*/




    private void setupViewPager(ViewPager viewPager){
        TabAdapter adapter = new TabAdapter(getSupportFragmentManager());
        adapter.addFragment(new TeacherClassPageTabSessionlist(), getResources().getString(R.string.teacher_class_page_tab_session));
        adapter.addFragment(new TeacherClassPageTabManage(), getResources().getString(R.string.teacher_class_page_tab_manage));
        adapter.addFragment(new TeacherClassPageTabClassinfo(), getResources().getString(R.string.teacher_class_page_tab_classinfo));
        viewPager.setAdapter(adapter);
    }




/*
    *//**
     * ActiveSessionDialog.ActiveSessionDialogListener function implementation
     *//*

    @Override
    public void onPositiveClick(){
        // TODO: take user to (active) question page
        //Toast.makeText(getApplicationContext(), "Hello from onPositiveClick", Toast.LENGTH_LONG).show();
        Intent intent = new Intent(getApplicationContext(), StudentQuestionCompletePage.class);
        intent.putExtra("ClassName", className);
        startActivity(intent);

    }

    @Override
    public void onNegativeClick(){
        // TODO: keep user on session page
        //Toast.makeText(getApplicationContext(), "Hello from onNegativeClick", Toast.LENGTH_LONG).show();
    }*/

}
