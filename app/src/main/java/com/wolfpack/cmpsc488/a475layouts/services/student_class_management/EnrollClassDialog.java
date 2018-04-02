package com.wolfpack.cmpsc488.a475layouts.services.student_class_management;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.DialogInterface;
import android.os.Bundle;
import android.util.Log;

/**
 * Created by peo5032 on 3/24/18.
 */

public class EnrollClassDialog extends DialogFragment{

    private static final String TAG = "EnrollClassDialog";
    public static final String EnrollClassDialogCLASS_TITLE = "CLASS_TITLE";
    public static final String EnrollClassDialogCLASS_TEACHER_FIRST_NAME = "CLASS_TEACHER_FIRST_NAME";
    public static final String EnrollClassDialogCLASS_TEACHER_LAST_NAME = "CLASS_TEACHER_LAST_NAME";


    public interface EnrollClassDialogListener{
        void onEnrollPositiveClick();
        void onEnrollNegativeClick();
    }

    EnrollClassDialogListener mListener;

    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState){

        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
        String title = savedInstanceState.getString(EnrollClassDialogCLASS_TITLE);
        String teacherFirstName = savedInstanceState.getString(EnrollClassDialogCLASS_TEACHER_FIRST_NAME);
        String teacherLastName = savedInstanceState.getString(EnrollClassDialogCLASS_TEACHER_LAST_NAME);

        //TODO: PASS IN TITLE
        builder.setTitle("Enroll in " + title + " ?")
                .setMessage("This course is taught by " + teacherFirstName + " " + teacherLastName)
                .setPositiveButton("Join", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        mListener.onEnrollPositiveClick();
                    }
                })
                .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        mListener.onEnrollNegativeClick();
                    }
                });

        return builder.create();
    }

    @Override
    public void onAttach(Activity activity){
        super.onAttach(activity);

        try{
            mListener = (EnrollClassDialogListener) activity;
        }
        catch (ClassCastException e){
            Log.e(TAG, "onAttach: " + e.getMessage());

        }
    }
}


