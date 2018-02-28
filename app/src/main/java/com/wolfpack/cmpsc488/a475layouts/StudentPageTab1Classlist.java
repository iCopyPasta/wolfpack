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
import android.widget.TextView;

import org.w3c.dom.Text;

public class StudentPageTab1Classlist extends Fragment {


    private static final String TAG = "SPTab1Classlist";

    private ListView mListViewClasses;
    private static String[] classlistTemp = {"CMPSC 460", "CMPSC 462", "CMPSC 463", "CMPSC 469","CMPSC 472", "CMPSC 488", "COMP 505", "COMP 511", "COMP 512", "COMP 519"};
    private static String[] classdesclistTemp = {"Principles of Programming Languages", "Data Structrues", "Design and Analysis of Algorithms", "Formal Languages with Applications", "Operating System Concepts", "Computer Science Project", "Theory of Computation", "Design and Anaylsis of Algorithms", "Advance Operating Systems", "Advanced Topics in Database Management Systems"};
    private static String[] classteacherlistTemp = {"Sukmoon Chang", "Jeremy Blum", "Jeremy Blum", "Sukmoon Chang", "Linda Null", "Hyuntae Na", "Thang Bui","Thang Bui", "Linda Null", "Linda Null"};


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_student_page_tab1_classlist, container, false);


        //populate list view
        // TODO: Use database to find classes that the student is enrolled
        //       Currently it is displaying a hard coded list for demonstrating purposes

        mListViewClasses = (ListView) rootView.findViewById(R.id.classListView);


        CustomAdapter mCustomAdapter = new CustomAdapter();

        mListViewClasses.setAdapter(mCustomAdapter);

        /*
        ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(
                getActivity(),
                android.R.layout.simple_list_item_1,
                classlistTemp);

        mListViewClasses.setAdapter(mAdapter);
        */

        mListViewClasses.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long id) {
                Intent intent = new Intent(getActivity(), ClassPage.class);
                intent.putExtra("ClassName", classlistTemp[i]);
                Log.i(TAG, "hello from onItemClick");

                startActivity(intent);
            }
        });



        return rootView;
    }


    private class CustomAdapter extends BaseAdapter{

        @Override
        public int getCount() {
            return classlistTemp.length;
        }

        @Override
        public Object getItem(int i) {
            return null;
        }

        @Override
        public long getItemId(int i) {
            return 0;
        }

        @Override
        public View getView(int i, View view, ViewGroup viewGroup) {
            view = getLayoutInflater().inflate(R.layout.listview_student_page_classlist, null);

            TextView class_name = (TextView) view.findViewById(R.id.classNameDisplay);
            TextView teacher_name = (TextView) view.findViewById(R.id.teacherNameDisplay);
            TextView class_description = (TextView) view.findViewById(R.id.classDescriptionDisplay);

            class_name.setText(classlistTemp[i]);
            teacher_name.setText(classteacherlistTemp[i]);
            class_description.setText(classdesclistTemp[i]);

            return view;
        }
    }

}