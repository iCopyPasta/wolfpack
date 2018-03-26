package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.annotation.SuppressLint;
import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.ArrayList;
import java.util.List;


class AnswerChoiceRecyclerViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {

    public RadioButton answerItem;
    private Context context;

    private ItemClickListener itemClickListener;



    public AnswerChoiceRecyclerViewHolder(View view, boolean isClickable) {
        super(view);
        answerItem = (RadioButton) view.findViewById(R.id.radioButtonAnswer);
        answerItem.setClickable(isClickable);

        context = view.getContext();

        itemView.setOnClickListener(this);
    }

    public void setItemClickListener(ItemClickListener itemClickListener){
        this.itemClickListener = itemClickListener;
    }

    public void setIsCorrectAnswer(boolean isCorrectAnswer){
        if (isCorrectAnswer){
            answerItem.setBackgroundColor(context.getResources().getColor(R.color.colorCorrectAnswer));
        }
        else
            answerItem.setBackgroundColor(context.getResources().getColor(R.color.colorWrongAnswer));

//        answerItem.setBackgroundColor((isCorrectAnswer) ?
//                context.getResources().getColor(R.color.colorCorrectAnswer):
//                context.getResources().getColor(R.color.colorWrongAnswer));

    }

    public void setIsStudentAnswer(boolean isStudentAnswer){
        answerItem.setChecked(isStudentAnswer);
    }


    @Override
    public void onClick(View view) {
        itemClickListener.onClick(view, getAdapterPosition());
    }
}



public class AnswerChoiceRecyclerAdapter extends RecyclerView.Adapter<AnswerChoiceRecyclerViewHolder>{


    public static final String TAG = "selectionAdapter";

    //turn into POJO
    private List<String> answers = new ArrayList<>();
    private List<Integer> correctAnswers = new ArrayList<>();
    private int studentAnswer;

    private List<AnswerChoiceRecyclerViewHolder> itemViews = new ArrayList<>();

    private Context context;
    private boolean isClickable;

    private ItemClickListener itemClickListener;

    public AnswerChoiceRecyclerAdapter(Context context, List<String> answers, List<Integer> correctAnswers, int studentAnswer, boolean isClickable){
        this.context = context;
        this.answers = answers;
        this.correctAnswers = correctAnswers;
        this.studentAnswer = studentAnswer;
        this.isClickable = isClickable;
    }

    public void setItemClickListener(ItemClickListener itemClickListener){
        this.itemClickListener = itemClickListener;
    }

    public String getItem(int position){
        return answers.get(position);
    }

    public RadioButton getItemView(int position){
        return itemViews.get(position).answerItem;
    }

    @Override
    public AnswerChoiceRecyclerViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        LayoutInflater inflater = LayoutInflater.from(parent.getContext());
        View item = inflater.inflate(R.layout.recyclerview_question_page_answer_choice_item, parent, false);

        return new AnswerChoiceRecyclerViewHolder(item, isClickable);
    }

    @Override
    public void onBindViewHolder(AnswerChoiceRecyclerViewHolder holder, int position) {
        holder.answerItem.setText((String) answers.get(position));
        holder.setItemClickListener(itemClickListener);
        holder.setIsCorrectAnswer(correctAnswers.contains(position));
        holder.setIsStudentAnswer(position == studentAnswer);

        itemViews.add(holder);
        Log.i(TAG, "itemView.size() = " + itemViews.size());
    }

    @Override
    public int getItemCount() {
        return answers.size();
    }








}

