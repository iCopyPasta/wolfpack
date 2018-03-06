package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.support.design.widget.TabLayout;
import android.support.v7.app.AppCompatActivity;

import android.support.v4.view.ViewPager;
import android.os.Bundle;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.TabAdapter;

public class StudentPage extends AppCompatActivity {

    private static final String TAG = "StudentPage";

    private TabAdapter mTabAdapter;
    private ViewPager mViewPager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_student_page);

        //set up the viewpager with the sections adapter
        mViewPager = (ViewPager) findViewById(R.id.studentPageViewPager);
        setupViewPager(mViewPager);

        TabLayout tabLayout = (TabLayout) findViewById(R.id.studentPageTabs);
        tabLayout.setupWithViewPager(mViewPager);

    }


    private void setupViewPager(ViewPager viewPager){
        TabAdapter adapter = new TabAdapter(getSupportFragmentManager());
        adapter.addFragment(new StudentPageTab1Classlist(), getResources().getString(R.string.student_page_tab1_classlist));
        adapter.addFragment(new StudentPageTab2AddClass(), getResources().getString(R.string.student_page_tab2_addclass));
        adapter.addFragment(new StudentPageTab3Settings(), getResources().getString(R.string.student_page_tab3_settings));
        viewPager.setAdapter(adapter);
    }





}











