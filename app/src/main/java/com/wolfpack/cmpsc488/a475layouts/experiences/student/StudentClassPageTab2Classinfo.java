package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import com.wolfpack.cmpsc488.a475layouts.R;

public class StudentClassPageTab2Classinfo extends Fragment
{

    private static final String TAG = "CPTab1Classinfo";


    private TextView mTextViewClassTitleData;
    private TextView mTextViewClassDescData;
    private TextView mTextViewClassOfferingData;
    private TextView mTextViewClassLocationData;
    private TextView mTextViewTeacherNameData;


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_student_class_page_tab2_classinfo, container, false);
        return rootView;
    }


    @Override
    public void onActivityCreated(Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);

        StudentClassPage activity = (StudentClassPage) getActivity();

        try {
            mTextViewClassTitleData = activity.findViewById(R.id.classTitleDataTextView);
            mTextViewClassDescData = activity.findViewById(R.id.classDescDataTextView);
            mTextViewClassOfferingData = activity.findViewById(R.id.classOfferingDataTextView);
            mTextViewClassLocationData = activity.findViewById(R.id.classLocationDataTextView);
            mTextViewTeacherNameData = activity.findViewById(R.id.teacherNameDataTextView);

            mTextViewClassTitleData.setText(activity.classTitle);
            mTextViewClassDescData.setText(activity.classDesc);
            mTextViewClassOfferingData.setText(activity.classOffering);
            mTextViewClassLocationData.setText(activity.classLocation);
            mTextViewTeacherNameData.setText(activity.teacherName);
        }
        catch(NullPointerException e){
            Log.d(TAG, e.getMessage());
            //throw e;
        }


        /*//populate list view
        // TODO: Use database to find classes that the student is enrolled
        //       Currently it is displaying a hard coded list for demonstrating purposes

        mListViewClassinfo = (ListView) rootView.findViewById(R.id.studentClassinfoListView);

        ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(
                getActivity(),
                android.R.layout.simple_list_item_1,
                classinfoTemp);

        mListViewClassinfo.setAdapter(mAdapter);*/

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


        ((Button) getActivity().findViewById(R.id.dropClassButton)).setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View view) {
                AlertDropDialog alertDialog = new AlertDropDialog();
                alertDialog.show(getActivity().getFragmentManager(), "Drop Confirmation");
            }
        });
    }

}
