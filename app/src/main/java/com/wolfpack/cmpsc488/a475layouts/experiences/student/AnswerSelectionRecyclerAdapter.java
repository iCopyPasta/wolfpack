package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.ArrayList;
import java.util.List;


class AnswerSelectionRecyclerViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {

    public CheckBox answerItem;

    private ItemClickListener itemClickListener;


    public AnswerSelectionRecyclerViewHolder(View view, boolean isClickable) {
        super(view);
        answerItem = (CheckBox) view.findViewById(R.id.checkBoxAnswer);
        answerItem.setClickable(isClickable);

        itemView.setOnClickListener(this);
    }

    public void setItemClickListener(ItemClickListener itemClickListener){
        this.itemClickListener = itemClickListener;
    }

    public void setIsCorrectAnswer(boolean isCorrectAnswer){
        if (isCorrectAnswer)
            answerItem.setBackgroundColor(answerItem.getContext().getResources().getColor(R.color.colorCorrectAnswer));
        else
            answerItem.setBackgroundColor(answerItem.getContext().getResources().getColor(R.color.colorWrongAnswer));

//        answerItem.setBackgroundColor((isCorrectAnswer) ?
//                answerItem.getContext().getResources().getColor(R.color.colorCorrectAnswer):
//                answerItem.getContext().getResources().getColor(R.color.colorWrongAnswer));

    }


    public void setIsStudentAnswer(boolean isStudentAnswer){
        answerItem.setChecked(isStudentAnswer);
    }


    @Override
    public void onClick(View view) {
        itemClickListener.onClick(view, getAdapterPosition());
    }
}



public class AnswerSelectionRecyclerAdapter extends RecyclerView.Adapter<AnswerSelectionRecyclerViewHolder>{


    public static final String TAG = "selectionAdapter";

    //turn into POJO
    private List<String> answers = new ArrayList<>();
    private List<Integer> correctAnswers = new ArrayList<>();
    private List<Integer> studentAnswers;

    private List<AnswerSelectionRecyclerViewHolder> itemViews = new ArrayList<>();

    private Context context;
    private boolean isClickable;

    private ItemClickListener itemClickListener;

    public AnswerSelectionRecyclerAdapter(Context context, List<String> answers, List<Integer> correctAnswers, List<Integer> studentAnswers, boolean isClickable){
        this.context = context;
        this.answers = answers;
        this.correctAnswers = correctAnswers;
        this.studentAnswers = studentAnswers;
        this.isClickable = isClickable;
    }
    public void setItemClickListener(ItemClickListener itemClickListener){
        this.itemClickListener = itemClickListener;
    }

    public String getItem(int position){
        return answers.get(position);
    }

    public CheckBox getItemView(int position){
        return itemViews.get(position).answerItem;
    }

    @Override
    public AnswerSelectionRecyclerViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        LayoutInflater inflater = LayoutInflater.from(parent.getContext());
        View item = inflater.inflate(R.layout.recyclerview_question_page_answer_selection_item, parent, false);

        return new AnswerSelectionRecyclerViewHolder(item, isClickable);
    }

    @Override
    public void onBindViewHolder(AnswerSelectionRecyclerViewHolder holder, int position) {
        holder.answerItem.setText((String) answers.get(position));
        holder.setItemClickListener(itemClickListener);
        holder.setIsCorrectAnswer(correctAnswers.contains(position));
        holder.setIsStudentAnswer(studentAnswers.contains(position));

        itemViews.add(holder);
        Log.i(TAG, "itemView.size() = " + itemViews.size());
    }

    @Override
    public int getItemCount() {
        return answers.size();
    }

}
