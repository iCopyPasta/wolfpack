package com.wolfpack.cmpsc488.a475layouts;

import android.support.design.widget.TabLayout;
import android.support.v7.app.AppCompatActivity;

import android.support.v4.view.ViewPager;
import android.os.Bundle;
import android.util.Log;
import android.support.v7.widget.Toolbar;

import authentication_services.WolfpackClient;

public class StudentPage extends AppCompatActivity {

    private static final String TAG = "StudentPage";
    private static WolfpackClient wolfpackClient;
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

    public static synchronized WolfpackClient getInstance(){
        if(wolfpackClient == null){
            return WolfpackClient.retrofit.create(WolfpackClient.class);
        } else{
            return wolfpackClient;
        }

    }





}











