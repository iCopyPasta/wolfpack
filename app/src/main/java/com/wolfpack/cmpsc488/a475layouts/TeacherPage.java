package com.wolfpack.cmpsc488.a475layouts;

import android.support.design.widget.TabLayout;
import android.support.v7.app.AppCompatActivity;

import android.support.v4.view.ViewPager;
import android.os.Bundle;
import android.util.Log;
import android.support.v7.widget.Toolbar;

public class TeacherPage extends AppCompatActivity {


    private static final String TAG = "TeacherPage";

    private TabAdapter mTabAdapter;
    private ViewPager mViewPager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_teacher_page);

        Log.i(TAG, "onCreate Start");

        //set up the viewpager with the sections adapter
        mViewPager = (ViewPager) findViewById(R.id.teacherPageViewPager);
        try {
            setupViewPager(mViewPager);

            Log.i(TAG, "onCreate after setupViewPager");

            TabLayout tabLayout = (TabLayout) findViewById(R.id.teacherPageTabs);
            tabLayout.setupWithViewPager(mViewPager);

            Log.i(TAG, "onCreate after tabLayout setup");
        }
        catch (NullPointerException e){
            Log.i(TAG, e.getMessage());
        }
    }


    private void setupViewPager(ViewPager viewPager){
        TabAdapter adapter = new TabAdapter(getSupportFragmentManager());
        adapter.addFragment(new TeacherPageTab1Classlist(), getResources().getString(R.string.teacher_page_tab1_classlist));
        adapter.addFragment(new TeacherPageTab2CreateClass(), getResources().getString(R.string.teacher_page_tab2_createclass));
        adapter.addFragment(new TeacherPageTab3Settings(), getResources().getString(R.string.teacher_page_tab3_settings));
        viewPager.setAdapter(adapter);
    }






}
