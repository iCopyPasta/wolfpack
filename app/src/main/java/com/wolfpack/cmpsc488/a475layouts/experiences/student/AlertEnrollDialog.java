package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.DialogInterface;
import android.os.Bundle;
import android.util.Log;

/**
 * Created by peo5032 on 4/15/18.
 */

public class AlertEnrollDialog extends DialogFragment {

    public interface AlertEnrollDialogListener{
        void onEnrollPositiveClick(String classId);
        void onEnrollNegativeClick();
    }

    public Bundle getInfo() {
        return info;
    }

    public void setInfo(Bundle info) {
        this.info = info;
    }

    private Bundle info;

    AlertEnrollDialog.AlertEnrollDialogListener mListener;

    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState){

        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());


        builder.setTitle("Enroll in " + info.getString("title") + "?")
                .setMessage("Press 'Join' in order to participate in class polls.")
                .setPositiveButton("Join", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        mListener.onEnrollPositiveClick(info.getString("class_id"));
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
            mListener = (AlertEnrollDialog.AlertEnrollDialogListener) activity;
        }
        catch (ClassCastException e){
            Log.e("AlertEnrollDia", "onAttach: " + e.getMessage());
            throw e;
        }
    }
}
