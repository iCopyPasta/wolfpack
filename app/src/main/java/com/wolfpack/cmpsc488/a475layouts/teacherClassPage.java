package com.wolfpack.cmpsc488.a475layouts;

import android.app.DialogFragment;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.widget.TextView;

import android.support.design.widget.TabLayout;
//import android.widget.Toolbar;


// TODO: pass class name and change toolbar name


public class teacherClassPage extends AppCompatActivity implements ActiveSessionDialog.ActiveSessionDialogListener {

    public static final String TAG = "ClassPage";

    private String className;
    private Toolbar classNameDisplay;


    private TabAdapter mTabAdapter;
    private ViewPager mViewPage;


    // TODO: set to false and assign activeSession based on if there is an active session currently
    boolean activeSession = true;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_class_page);


        Bundle bundle;
        try {
            //get class name
            bundle = getIntent().getExtras();
            className = (String) bundle.get("ClassName");

            Log.i(TAG, "in try before classNameDisplay assignment");

            //displays class name (eg CMPSC 121) in the toolbar
            classNameDisplay = (Toolbar) findViewById(R.id.toolbarteacherClassPage);
            setSupportActionBar(classNameDisplay);
            classNameDisplay.setTitle(className);

            Log.i(TAG, "className = " + className);

            //setup up viewpager (set up transition between tabs)
            mViewPage = (ViewPager) findViewById(R.id.sessionPageViewPager);
            setupViewPager(mViewPage);

            TabLayout tabLayout = (TabLayout) findViewById(R.id.sessionPageTabs);
            tabLayout.setupWithViewPager(mViewPage);


            Log.i(TAG, "end of onCreate");
        }
        catch (NullPointerException e){
            Log.i(TAG,"teacherClassPage got NullPointerException");
            Log.i(TAG, e.getMessage());
        }

    }


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
        adapter.addFragment(new ClassPageTab1Sessionlist(), getResources().getString(R.string.class_page_tab1_session));
        adapter.addFragment(new ClassPageTab2Classinfo(), getResources().getString(R.string.class_page_tab2_classinfo));
        viewPager.setAdapter(adapter);
    }





    /**
     * ActiveSessionDialog.ActiveSessionDialogListener function implementation
     */

    @Override
    public void onPositiveClick(){
        // TODO: take user to (active) question page
        //Toast.makeText(getApplicationContext(), "Hello from onPositiveClick", Toast.LENGTH_LONG).show();
        Intent intent = new Intent(getApplicationContext(), QuestionPage.class);
        intent.putExtra("ClassName", className);
        startActivity(intent);

    }

    @Override
    public void onNegativeClick(){
        // TODO: keep user on session page
        //Toast.makeText(getApplicationContext(), "Hello from onNegativeClick", Toast.LENGTH_LONG).show();
    }

}
