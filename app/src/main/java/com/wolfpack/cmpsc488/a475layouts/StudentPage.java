package com.wolfpack.cmpsc488.a475layouts;

import android.content.Intent;
import android.support.design.widget.TabLayout;
import android.support.v7.app.AppCompatActivity;

import android.support.v4.view.ViewPager;
import android.os.Bundle;
import android.util.Log;
import android.view.View;

import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class StudentPage extends AppCompatActivity {

    private static final String TAG = "StudentPage";

    private StudentPageAdapter mStudentPageAdapter;
    private ViewPager mViewPager;


    //private ListView listView;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_student_page);

        //Log.d(TAG, "onCreate: Starting");


        //set up the viewpager with the sections adapter
        mViewPager = (ViewPager) findViewById(R.id.container);
        setupViewPager(mViewPager);

        TabLayout tabLayout = (TabLayout) findViewById(R.id.tabs);
        tabLayout.setupWithViewPager(mViewPager);


    }


    private void setupViewPager(ViewPager viewPager){
        StudentPageAdapter adapter = new StudentPageAdapter(getSupportFragmentManager());
        adapter.addFragment(new StudentPageTab1Classlist(), getResources().getString(R.string.student_page_tab1_classlist));
        adapter.addFragment(new StudentPageTab2AddClass(), getResources().getString(R.string.student_page_tab2_addclass));
        viewPager.setAdapter(adapter);
    }





}











