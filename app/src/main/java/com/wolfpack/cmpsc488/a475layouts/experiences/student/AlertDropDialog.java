package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.DialogInterface;
import android.os.Bundle;


public class AlertDropDialog extends DialogFragment {

    public Bundle getInfo() {
        return info;
    }

    public void setInfo(Bundle info) {
        this.info = info;
    }

    private Bundle info;

    public interface AlertDropDialogListener{
        void onDropPositiveClick();
        void onDropNegativeClick();
    }

    AlertDropDialogListener mListener;

    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState){

        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());

        builder.setTitle("Confirm Dropping Class")
                .setMessage("Are you sure you want to drop this class? You must enroll again in order to participate in the future.")
                .setPositiveButton("Drop", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        mListener.onDropPositiveClick();
                    }
                })
                .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        mListener.onDropNegativeClick();
                    }
                });

        return builder.create();
    }

    @Override
    public void onAttach(Activity activity){
        super.onAttach(activity);

        try{
            mListener = (AlertDropDialogListener) activity;
        }
        catch (ClassCastException e){
            System.out.println(e.getMessage());
            throw e;
        }
    }

}
