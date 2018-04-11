package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.DialogInterface;
import android.os.Bundle;

/**
 * Created by peo5032 on 4/10/18.
 */

public class AlertLeaveNewSessionDialog extends DialogFragment{

    public Bundle getInfo() {
        return info;
    }

    public void setInfo(Bundle info) {
        this.info = info;
    }

    private Bundle info;

    public interface AlertLeaveNewSessionDialogListener{
        void onLeavePositiveClick();
        void onLeaveNegativeClick();
    }

    AlertLeaveNewSessionDialogListener mListener;

    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState){

        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());

        builder.setTitle("Expired Session")
                .setMessage("Your session has expired! You will not receive active questions for your class. Hit the 'back' arrow to get the most updated session.")
                .setPositiveButton("Leave", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        mListener.onLeavePositiveClick();
                    }
                })
                .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        mListener.onLeaveNegativeClick();
                    }
                });

        return builder.create();
    }

    @Override
    public void onAttach(Activity activity){
        super.onAttach(activity);

        try{
            mListener = (AlertLeaveNewSessionDialogListener) activity;
        }
        catch (ClassCastException e){
            System.out.println(e.getMessage());
            throw e;
        }
    }

}
