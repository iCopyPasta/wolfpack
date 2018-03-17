package com.wolfpack.cmpsc488.a475layouts.experiences.teacher;


import android.content.Intent;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.ListView;
import android.widget.TextView;

import com.wolfpack.cmpsc488.a475layouts.experiences.student.StudentClassPage;
import com.wolfpack.cmpsc488.a475layouts.R;

public class TeacherPageTab1Classlist extends Fragment {

    private static final String TAG = "PPTab1Classlist";

    private ListView mListViewClasses;
    private static String[] classlistTemp = {"CMPSC 441", "CMPSC 457", "CMPSC 460", "CMPSC 469"};
    private static String[] classdesclistTemp = {"Artificial Intelligence", "Computer Graphics Algorithms", "Principles of Programming Languages", "Formal Languages with Applications"};
    //private static String[] classteacherlistTemp = {"Sukmoon Chang", "Sukmoon Chang", "Sukmoon Chang", "Sukmoon Chang"};


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_teacher_page_tab1_classlist, container, false);

        Log.i(TAG, "onCreateView");

        //populate list view
        // TODO: Use database to find classes that the student is enrolled
        //       Currently it is displaying a hard coded list for demonstrating purposes

        mListViewClasses = (ListView) rootView.findViewById(R.id.profClassListView);


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
                Intent intent = new Intent(getActivity(), TeacherClassPage.class);
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
            view = getLayoutInflater().inflate(R.layout.listview_teacher_page_classlist, null);

            //TextView teacher_name = (TextView) view.findViewById(R.id.teacherNameDisplay);
            TextView class_name = (TextView) view.findViewById(R.id.profClassNameDisplay);
            TextView class_description = (TextView) view.findViewById(R.id.profClassDescriptionDisplay);

            //teacher_name.setText(classteacherlistTemp[i]);
            class_description.setText(classdesclistTemp[i]);
            class_name.setText(classlistTemp[i]);

            return view;
        }
    }


}
