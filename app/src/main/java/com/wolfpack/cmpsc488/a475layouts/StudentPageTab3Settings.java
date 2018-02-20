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
import android.widget.ListView;

public class StudentPageTab3Settings extends Fragment {

    private static final String TAG = "SPTab3Settings";

    private ListView mListViewSettings;
    private static String[] settingsListTemp = {"User Information", "Help", "About", "Logout"};


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_student_page_tab3_settings, container, false);

        mListViewSettings = (ListView) rootView.findViewById(R.id.settingsListView);

        ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(
                getActivity(),
                android.R.layout.simple_list_item_1,
                settingsListTemp);


        mListViewSettings.setAdapter(mAdapter);

        mListViewSettings.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
                Intent intent = null;

                //Activity to start depends on what is clicked
                switch (settingsListTemp[position]) {
                    case "User Information":
                        break;
                    case "Help":
                        break;
                    case "About":
                        break;
                    case "Logout":
                        //Not the correct way to logout (back button still works)
                        //  and it does not quit the session with the server
                        intent = new Intent(getActivity(), MainPage.class);
                        break;
                    default:
                        Log.i(TAG, "Click out of bounds");
                }

                try {
                    startActivity(intent);
                } catch (NullPointerException e) {
                    Log.i(TAG, "No activity start");
                }
            }
        });

        return rootView;
    }



}
