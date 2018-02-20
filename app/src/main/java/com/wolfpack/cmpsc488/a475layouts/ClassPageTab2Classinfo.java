package com.wolfpack.cmpsc488.a475layouts;

import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class ClassPageTab2Classinfo extends Fragment {

    private static final String TAG = "CPTab1Classinfo";

    private ListView mListViewClassinfo;
    private static String[] classinfoTemp = {"Class ID", "Professor"};

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_class_page_tab2_classinfo, container, false);


        //populate list view
        // TODO: Use database to find classes that the student is enrolled
        //       Currently it is displaying a hard coded list for demonstrating purposes

        mListViewClassinfo = (ListView) rootView.findViewById(R.id.classinfoListView);

        ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(
                getActivity(),
                android.R.layout.simple_list_item_1,
                classinfoTemp);

        mListViewClassinfo.setAdapter(mAdapter);

        /*
        mListViewClassinfo.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int position, long id){
                Intent intent = new Intent(getActivity(), ClassPage.class);
                //intent.putExtra("ClassName", mListViewClassinfo.getItemAtPosition(position).toString());
                Log.i(TAG, "hello from onItemClick");

                startActivity(intent);
            }
        });
        */

        return rootView;
    }


}
