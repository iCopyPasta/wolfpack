package com.wolfpack.cmpsc488.a475layouts;

import android.content.Intent;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.ListView;

public class StudentPageTab1Classlist extends Fragment {


    private static final String TAG = "SPTab1Classlist";

    private ListView mListViewClasses;
    private static String[] classlistTemp = {"CMPSC 121", "CMPSC 122", "CMPSC 221", "CMPSC 312", "CMPSC 360", "CMPSC 430", "CMPSC 460", "CMPSC 462", "CMPSC 463", "CMPSC 470", "CMPSC 472", "CMPSC 487W", "CMPSC 488", "COMP 505", "COMP 511", "COMP 512", "COMP 519"};


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_student_page_tab1_classlist, container, false);


        //populate list view
        // TODO: Use database to find classes that the student is enrolled
        //       Currently it is displaying a hard coded list for demonstrating purposes

        mListViewClasses = (ListView) rootView.findViewById(R.id.classListView);


        ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(
                getActivity(),
                android.R.layout.simple_list_item_1,
                classlistTemp);

        mListViewClasses.setAdapter(mAdapter);

        mListViewClasses.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
                Intent intent = new Intent(getActivity(), ClassPage.class);
                intent.putExtra("ClassName", mListViewClasses.getItemAtPosition(position).toString());
                Log.i(TAG, "hello from onItemClick");

                startActivity(intent);
            }
        });


        return rootView;
    }

}