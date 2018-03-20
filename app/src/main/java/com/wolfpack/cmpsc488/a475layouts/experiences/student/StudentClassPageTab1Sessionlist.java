package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.content.Intent;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;

import com.wolfpack.cmpsc488.a475layouts.R;

public class StudentClassPageTab1Sessionlist extends Fragment {

    private static final String TAG = "TCPTab1Sessionlist";

    private String className;

    private ListView mListViewSessions;
    private static String[] sessionlistTemp = {"Session 01", "Session 02", "Session XD"};

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_student_class_page_tab1_sessionlist, container, false);

        try {
            //populate list view
            // TODO: Use database to find classes that the student is enrolled
            //       Currently it is displaying a hard coded list for demonstrating purposes

            className = ((StudentClassPage) getActivity()).getClassName();

            mListViewSessions = (ListView) rootView.findViewById(R.id.studentSessionListView);

            ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(
                    getActivity(),
                    android.R.layout.simple_list_item_1,
                    sessionlistTemp);

            mListViewSessions.setAdapter(mAdapter);

            mListViewSessions.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
                    //go to a completed session (no constant checking done on StudentSessionPage)
                    Intent intent = new Intent(getActivity(), StudentSessionPage.class);
                    intent.putExtra("className", className);
                    intent.putExtra("sessionName", mListViewSessions.getItemAtPosition(position).toString());
                    intent.putExtra("isActive", false);
                    startActivity(intent);
                }
            });
        }
        catch (NullPointerException e){
            Log.i(TAG, e.toString());
            throw e;
        }

        return rootView;
    }






}
