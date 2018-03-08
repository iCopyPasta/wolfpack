package com.wolfpack.cmpsc488.a475layouts.experiences.student;


import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;

import com.wolfpack.cmpsc488.a475layouts.MainPage;
import com.wolfpack.cmpsc488.a475layouts.R;

public class StudentPageTab3Settings extends Fragment {

    private static final String TAG = "SPTab3Settings";

    private ListView mListViewSettings;
    //TODO: we could move settingsListTemp array into the strings.xml file as it is a static list pertaining to the particular page
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
                        // TODO: exit session when leaving
                        //SHARED PREFERENCES UPDATE
                        Context context = view.getContext();
                        SharedPreferences sharedPref = context.getSharedPreferences(
                                getString(R.string.preference_file_key), Context.MODE_PRIVATE);

                        SharedPreferences.Editor editor = sharedPref.edit();
                        editor.putBoolean(getString(R.string.SKIP_LOGIN), true);
                        editor.putString(getString(R.string.USER_MODE), "none");

                        editor.apply(); //dedicate to persistant storage in background thread
                        
                        intent = new Intent(getActivity(), MainPage.class);
                        Log.d(TAG, "onItemClick: transferring to MainPage.class");
                        startActivity(intent);
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
