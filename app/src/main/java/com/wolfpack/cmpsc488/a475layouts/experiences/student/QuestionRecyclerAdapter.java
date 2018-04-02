package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.wolfpack.cmpsc488.a475layouts.R;

import java.util.ArrayList;
import java.util.List;


class QuestionRecyclerViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {

    public TextView questionDesc;

    private ItemChoiceClickListener itemChoiceClickListener;


    public QuestionRecyclerViewHolder(View view) {
        super(view);
        questionDesc = (TextView) view.findViewById(R.id.textViewQuestion);

        itemView.setOnClickListener(this);
    }

    public void setItemChoiceClickListener(ItemChoiceClickListener itemChoiceClickListener){
        this.itemChoiceClickListener = itemChoiceClickListener;
    }


    @Override
    public void onClick(View view) {
        itemChoiceClickListener.onClick(view, getAdapterPosition());
    }
}



public class QuestionRecyclerAdapter extends RecyclerView.Adapter<QuestionRecyclerViewHolder>{

    //turn into POJO
    private List<String> items = new ArrayList<>();
    private Context context;

    private ItemChoiceClickListener itemChoiceClickListener;

    public QuestionRecyclerAdapter(List<String> items, Context context){
        this.items = items;
        this.context = context;
    }

    public void setItemChoiceClickListener(ItemChoiceClickListener itemChoiceClickListener){
        this.itemChoiceClickListener = itemChoiceClickListener;
    }

    public String getItem(int position){
        return items.get(position);
    }

    @Override
    public QuestionRecyclerViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        LayoutInflater inflater = LayoutInflater.from(parent.getContext());
        View item = inflater.inflate(R.layout.recyclerview_session_page_question, parent, false);

        return new QuestionRecyclerViewHolder(item);
    }

    @Override
    public void onBindViewHolder(QuestionRecyclerViewHolder holder, int position) {
        holder.questionDesc.setText((String) items.get(position));
        holder.setItemChoiceClickListener(itemChoiceClickListener);
    }

    @Override
    public int getItemCount() {
        return items.size();
    }








}
