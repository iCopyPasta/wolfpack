package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CheckBox;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.QuestionInformation;

import java.util.ArrayList;
import java.util.List;




public class AnswerChoiceRecyclerAdapter
        extends RecyclerView.Adapter<AnswerChoiceRecyclerAdapter.AnswerChoiceRecyclerViewHolder>
{


    public static final String TAG = "selectionAdapter";

    //turn into POJO
    private List<QuestionInformation> questionInformationList = new ArrayList<>();
    private List<String> answers = new ArrayList<>();
    private List<Integer> correctAnswers = new ArrayList<>();
    private List<Integer> studentAnswers = null;

    private List<AnswerChoiceRecyclerViewHolder> itemViews = new ArrayList<>();

    private Context context;
    private boolean isClickable;

    private ItemChoiceClickListener itemChoiceClickListener = null;

    //for complete page
    public AnswerChoiceRecyclerAdapter(Context context, List<String> answers, List<Integer> correctAnswers, List<Integer> studentAnswers, boolean isClickable){
        this.context = context;
        this.answers = answers;
        this.correctAnswers = correctAnswers;
        this.studentAnswers = studentAnswers;
        this.isClickable = isClickable;
    }

    //for active page
    public AnswerChoiceRecyclerAdapter(Context context, List<String> answers, List<Integer> correctAnswers, boolean isClickable){
        this.context = context;
        this.answers = answers;
        this.correctAnswers = correctAnswers;
        this.isClickable = isClickable;
    }

    public void setItemChoiceClickListener(ItemChoiceClickListener itemChoiceClickListener){
        this.itemChoiceClickListener = itemChoiceClickListener;
    }

    public String getItem(int position){
        return answers.get(position);
    }

    public CheckBox getItemView(int position){
        return itemViews.get(position).answerItem;
    }

    @Override
    public AnswerChoiceRecyclerViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        LayoutInflater inflater = LayoutInflater.from(parent.getContext());
        View item = inflater.inflate(R.layout.recyclerview_question_page_answer_selection_item, parent, false);

        return new AnswerChoiceRecyclerViewHolder(item, isClickable);
    }

    @Override
    public void onBindViewHolder(AnswerChoiceRecyclerViewHolder holder, int position) {
        holder.answerItem.setText((String) answers.get(position));
        //holder.setItemChoiceClickListener(itemChoiceClickListener);

        //if not active question
        if (studentAnswers != null) {
            holder.setIsCorrectAnswer(correctAnswers.contains(position));
            holder.setIsStudentAnswer(studentAnswers.contains(position));
        }

        itemViews.add(holder);
        Log.i(TAG, "itemView.size() = " + itemViews.size());
    }

    @Override
    public int getItemCount() {
        return answers.size();
    }



    public void onQuestionCompleted(){

        Log.d(TAG, "itemViews = " + itemViews);
        Log.d(TAG, "correctAnswers = " + correctAnswers);

        Log.d(TAG, "starting\n\n");

        int i = 0;
        for (AnswerChoiceRecyclerViewHolder holder : itemViews){
            Log.d(TAG, "holder(" + i + ") text: " + holder.answerItem.getText() + " || isCorrect? " + correctAnswers.contains(i));
            holder.answerItem.setClickable(false);
            holder.setIsCorrectAnswer(correctAnswers.contains(i));
            i++;
        }

        itemChoiceClickListener = null;

        //notifyDataSetChanged();

    }








    // View Holder
    class AnswerChoiceRecyclerViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {

        public CheckBox answerItem;

        //private ItemChoiceClickListener itemChoiceClickListener;


        public AnswerChoiceRecyclerViewHolder(View view, boolean isClickable) {
            super(view);
            answerItem = (CheckBox) view.findViewById(R.id.checkBoxAnswer);
            answerItem.setClickable(isClickable);
            answerItem.setOnClickListener(this);
            //itemView.setOnClickListener(this);
        }

//        public void setItemChoiceClickListener(ItemChoiceClickListener itemChoiceClickListener){
//            this.itemChoiceClickListener = itemChoiceClickListener;
//        }

        public void setIsCorrectAnswer(boolean isCorrectAnswer) {
            if (isCorrectAnswer)
                //answerItem.setBackgroundColor(answerItem.getContext().getResources().getColor(R.color.colorCorrectAnswer));
                answerItem.setTextColor(answerItem.getContext().getResources().getColor(R.color.colorCorrectAnswer));
            else
                //answerItem.setBackgroundColor(answerItem.getContext().getResources().getColor(R.color.colorWrongAnswer));
                answerItem.setTextColor(answerItem.getContext().getResources().getColor(R.color.colorWrongAnswer));


//        answerItem.setBackgroundColor((isCorrectAnswer) ?
//                answerItem.getContext().getResources().getColor(R.color.colorCorrectAnswer):
//                answerItem.getContext().getResources().getColor(R.color.colorWrongAnswer));

        }


        private void setIsStudentAnswer(boolean isStudentAnswer) {
            answerItem.setChecked(isStudentAnswer);
        }


        //TODO: this onClick is not working
        @Override
        public void onClick(View view) {
            if (itemChoiceClickListener != null) {
                itemChoiceClickListener.onClick(view, getAdapterPosition());
                Log.w(TAG,"itemChoiceClickListener != null");
            }
            Log.w(TAG, "itemChoiceClickListener == null for some damn reason");

        }
    }




}
