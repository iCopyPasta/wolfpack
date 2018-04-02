package com.wolfpack.cmpsc488.a475layouts.experiences.teacher;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;

import com.wolfpack.cmpsc488.a475layouts.R;

public class TeacherClassPageTabManage extends Fragment {

    private static final String TAG = "TCPTabManage";

    private Button manageSessions;
    private Button manageQuestions;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState){
        View rootView = inflater.inflate(R.layout.fragment_teacher_class_page_tab_manage, container, false);

        manageSessions = (Button) rootView.findViewById(R.id.toManageSessions);
        manageQuestions = (Button) rootView.findViewById(R.id.toManageQuestions);

        manageSessions.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view){
                Intent intent = new Intent(getActivity(), TeacherManageSessionsPage.class);
                Log.i(TAG, "starting manage sessions page");
                startActivity(intent);
            }
        });

        manageQuestions.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view){
                Intent intent = new Intent(getActivity(), TeacherManageQuestionsPage.class);
                Log.i(TAG, "starting manage sessions page");
                startActivity(intent);
            }
        });


        return rootView;
    }

}
