package com.wolfpack.cmpsc488.a475layouts;

import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

public class TeacherPageTab2CreateClass extends Fragment {

    private static final String TAG = "PPTab2CreateClass";


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_professor_page_tab2_createclass, container, false);

        Log.i(TAG, "onCreateView");

        //TODO: add fucntionality to create class fragment





        return rootView;
    }



}
